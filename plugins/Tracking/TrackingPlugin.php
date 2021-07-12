<?php

use plugin\tracking\vo\ServiceValuesObject;
use tracking\providers\IProvider;

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
    
    public function onCronSyncUsersDataByProvider(IProvider $provider)
    {
    
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
        $instance->onInit($setting);
        
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
}