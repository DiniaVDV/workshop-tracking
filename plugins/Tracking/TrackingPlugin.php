<?php

use plugin\tracking\ITrackingDataAccessObject;
use plugin\tracking\ITrackingService;
use plugin\tracking\vo\ServiceValuesObject;
use plugin\tracking\vo\SettingValuesObject;
use tracking\providers\IProviderIssue;
use tracking\providers\IProviderCommit;

class TrackingPlugin extends ObjectPlugin
{
    public const TYPE_ISSUE  = 'issue';
    public const TYPE_COMMIT = 'commit';
    
    public function onCronSyncUsersIssues(): bool
    {
        $settings = $this->_searchSettingsByType(static::TYPE_ISSUE);
    
        foreach ($settings as $setting) {
            $provider = $this->_getProviderInstanceBySetting($setting);
        
            $data = $provider->loadRemoteData();
        
            foreach ($data as $values)
            {
                $provider->create($values);
            }
        }
        
        return true;
    }
    
    public function onCronSyncUsersCommits(): bool
    {
        $settings = $this->_searchSettingsByType(static::TYPE_COMMIT);
    
        foreach ($settings as $setting) {
            $provider = $this->_getProviderInstanceBySetting($setting);
        
            $data = $provider->loadRemoteData();
        
            foreach ($data as $values)
            {
                $provider->create($values);
            }
        }
        
        return true;
    }
    
    public function onCronSyncUsersCommitsByProvider(IProviderCommit $provider, ITrackingDataAccessObject $dao)
    {
        $settings = $this->_searchSettingsByType($provider::TYPE);
        $result = array();
    
        foreach ($settings as $setting) {
            $service = $this->_createServiceInstance($setting);
        
            $provider->setService($service);
        
            $remoteUserCode = $settings->getRemoteUserCode();
        
            if (!$remoteUserCode) {
                $provider->getService()->loadUserID();
            }
        
            $data = $provider->loadRemoteData();
        
            foreach ($data as $values)
            {
                $result[] = $dao->createCommit($values);
            }
        }
    
        return $result;
    }
    
    public function onCronSyncUsersIssuesByProvider(IProviderIssue $provider, ITrackingDataAccessObject $dao): array
    {
        $settings = $this->_searchSettingsByType($provider::TYPE);
        $result = array();
    
        foreach ($settings as $setting) {
            $service = $this->_createServiceInstance($setting);
            
            $provider->setService($service);
            
            $remoteUserCode = $settings->getRemoteUserCode();
    
            if (!$remoteUserCode) {
                $provider->getService()->loadUserID();
            }
            
            $data = $provider->loadRemoteData();
        
            foreach ($data as $values)
            {
                $result[] = $dao->createIssue($values);
            }
        }
        
        return $result;
    }
    
    private function _onSyncBySettings(array $settings, IProviderCommit $provider): array
    {
        $result = array();
    
        foreach ($settings as $setting) {
            $provider->onInit($setting, $dao);
        
            $data = $provider->loadRemoteData();
        
            foreach ($data as $values)
            {
                $result[] = $provider->create($values);
            }
        }
        
        return $result;
    }
    
    private function _getProviderInstanceBySetting(SettingValuesObject $setting): IProvider
    {
        $service = $this->_getServiceByID($setting->getServiceID());
        
        if (!$service) {
            $msg = __('Could Not Find Service By ID "%s"', $setting->getServiceID());
            throw new SystemException($msg);
        }
        
        $className = $service->geClassName();
        
        if (class_exists($className)) {
            $msg = __('Could Not Find Class "%s"', $className);
            throw new SystemException($msg);
        }
        
        $instance = new $className();
        $instance->onInit($setting, $this->object);
        
        return $instance;
    }
    
    private function _searchSettingsByType(string $type)
    {
        $search = array(
            'type' => $type,
        );
        
        $values = $this->object->searchSettings($search);
        
        if ($values) {
            $values = $this->_convertDataToValuesObject($values);
        }
        
        return $values;
    }
    
    private function _convertDataToValuesObject(array $values): array
    {
        $vos = array();
        
        foreach ($values as $row) {
            $vos[] = new SettingValuesObject($row);
        }
        
        return $vos;
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
    
    
    private function _createServiceInstance(SettingValuesObject $settings): ITrackingService
    {
        $className = 'tracking\\libs\\'.ucfirst($settings->getIdent());
        
        return new $className($settings);
    }
}