<?php

require_once 'vo/autoload.php';

class TrackingPlugin extends ObjectPlugin
{
    public function onCronSyncUsersData(): bool
    {
        $users = $this->plugin->users->search();
        
        foreach ($users as $user) {
            $settings = $this->_searchSettingsByUser($user);
            
            if (!$settings) {
                continue;
            }
            
            $this->_updateBySettings($settings);
        }
        return true;
    }
    
    private function _updateBySevices(array $settings)
    {
        foreach ($settings as $setting) {
            $provider = $this->_getProviderInstanceBySetting($setting);
            
            $data = $provider->loadRemoteData($setting);
            
            foreach ($data as $values)
            {
                $provider->create($values);
            }
        }
    }
    
    private function _getProviderInstanceBySetting(SettingValuesObject $setting): IProvider
    {
        $className = ucfirst($setting->getIdent()).'Provider';
        
        $path = $this->_getProviderPathByName($className);
        
        if (!file_exists($path)) {
            $msg = __('Could Not Find File "%s"', $path);
            throw new SystemException($msg);
        }
        
        require_once $path;
        
        if (class_exists($className)) {
            $msg = __('Could Not Class "%s"', $className);
            throw new SystemException($msg);
        }
        
        return new $className();
    }
    
    private function _getProviderPathByName(string $name)
    {
        return sprintf('%s/providers/%s.php', __DIR__, $name);
    }
    
    private function _updateBySettings(UserValuesObject $user)
    {
        $search = array(
            'id_user' => $user->getID(),
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