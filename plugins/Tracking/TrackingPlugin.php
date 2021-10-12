<?php

use plugin\tracking\ITrackingDataAccessObject;
use plugin\tracking\providers\IProvider;
use plugin\tracking\vo\ServiceValuesObject;
use plugin\tracking\vo\SettingValuesObject;
use tracking\services\IServiceIssue;
use tracking\services\IServiceCommit;

class TrackingPlugin extends ObjectPlugin
{
    public const TYPE_ISSUE  = 'issue';
    public const TYPE_COMMIT = 'commit';
    
    public function onCronSyncUsersIssues(): bool
    {
        $settings = $this->_getSettingsByType(static::TYPE_ISSUE);
   
        foreach ($settings as $setting) {
            $service = $this->_getServiceIssueInstanceBySetting($setting, $this->object);
        
            $data = $service->loadRemoteData();
        
            foreach ($data as $values)
            {
                $service->create($values);
            }
        }
        
        return true;
    }
    
    public function onCronSyncUsersCommits(): bool
    {
        $settings = $this->_getSettingsByType(static::TYPE_COMMIT);
        
        foreach ($settings as $setting) {
            $service = $this->_getServiceCommitInstanceBySetting($setting, $this->object);
        
            $data = $service->loadRemoteData();
        
            foreach ($data as $values)
            {
                $service->create($values);
            }
        }
        
        return true;
    }
    
    public function onCronSyncUsersCommitsByProvider(IServiceCommit $service)
    {
        $settings = $this->_getSettingsByTypeAndPlatform($service->getType(), $service->getPlatform());
    
        if (!$settings) {
            throw new SystemException('Not Found Users Connected To Service');
        }
    
        $result = array();
    
        foreach ($settings as $setting) {
            $provider = $this->_createProviderInstance($setting);
    
            $service->setProvider($provider);
        
            $data = $service->loadRemoteData();
        
            foreach ($data as $values)
            {
                $result[] = $service->create($values);
            }
        }
    
        return $result;
    }
    
    public function onCronSyncUsersIssuesByProvider(IServiceIssue $service): array
    {
        $settings = $this->_getSettingsByTypeAndPlatform($service->getType(), $service->getPlatform());
        
        if (!$settings) {
            throw new SystemException('Not Found Users Connected To Service');
        }
        
        $result = array();
        
        foreach ($settings as $setting) {
            $provider = $this->_createProviderInstance($setting);
    
            $service->setProvider($provider);
            
            $data = $service->loadRemoteData();
        
            foreach ($data as $values)
            {
                $result[] = $service->create($values);
            }
        }
        
        return $result;
    }
    
    private function _getServiceCommitInstanceBySetting(
        SettingValuesObject $setting, ITrackingDataAccessObject $dao
    ): IServiceCommit
    {
        $service = $this->_getServiceByID($setting->getServiceID());
        
        if (!$service) {
            $msg = __('Could Not Find Service By ID "%s"', $setting->getServiceID());
            throw new SystemException($msg);
        }
        
        $className = $service->geClassName();
        
        if (!class_exists($className)) {
            $msg = __('Could Not Find Class "%s"', $className);
            throw new SystemException($msg);
        }
        
        $instance = new $className();
        $instance->onInit($setting, $dao);
        
        return $instance;
    }
    
    private function _getServiceIssueInstanceBySetting(
        SettingValuesObject $setting, ITrackingDataAccessObject $dao
    ): IServiceIssue
    {
        $service = $this->_getServiceByID($setting->getServiceID());
    
        if (!$service) {
            $msg = __('Could Not Find Service By ID "%s"', $setting->getServiceID());
            throw new SystemException($msg);
        }
    
        $className = $service->geClassName();
    
        if (!class_exists($className)) {
            $msg = __('Could Not Find Class "%s"', $className);
            throw new SystemException($msg);
        }
    
        $instance = new $className();
        $instance->onInit($setting, $dao);
    
        return $instance;
    }
    
    private function _getSettingsByType(string $type)
    {
        $search = array(
            'type' => $type,
        );
        
        $values = $this->object->searchSettings($search);
        
        if ($values) {
            $values = SettingValuesObject::convert($values);
        }
        
        return $values;
    }

    private function _getSettingsByTypeAndPlatform(string $type, string $platform)
    {
        $search = array(
            'type'     => $type,
            'platform' => $platform,
        );
        
        $service = $this->object->getService($search);
        
        if (!$service) {
            throw new SystemException('Not Found Service');
        }
    
        $search = array(
            'id_service' => $service['id'],
        );
        
        $values = $this->object->searchSettings($search);
        
        if ($values) {
            $values = SettingValuesObject::convert($values);
        }
        
        return $values;
    }

    private function _getServiceByID(int $id): ?ServiceValuesObject
    {
        $values = $this->object->getServiceByID($id);
        $service = null;
        
        if ($values) {
            $service = new ServiceValuesObject($values);
        }
        
        return $service;
    }
    
    
    private function _createProviderInstance(SettingValuesObject $settings): IProvider
    {
        $className = 'tracking\\providers\\'.ucfirst($settings->getIdent());
        
        return new $className($settings);
    }
}