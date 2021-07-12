<?php

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
        $className = $setting->getClassName();
        
        if (class_exists($className)) {
            $msg = __('Could Not Class "%s"', $className);
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
}