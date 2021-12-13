<?php

use plugin\tracking\ITrackingDataAccessObject;
use plugin\tracking\providers\IProviderCommit;
use plugin\tracking\providers\IProviderIssue;
use plugin\tracking\vo\ServiceValuesObject;
use plugin\tracking\vo\UserSettingValuesObject;
use tracking\services\IServiceIssue;
use tracking\services\IServiceCommit;

class TrackingPlugin extends ObjectPlugin
{
    public function onCronSyncUsersIssues(DateTime $date): bool
    {
        $userSettings = $this->_getUserSettingsByType(ServiceValuesObject::TYPE_ISSUE);
        
        foreach ($userSettings as $userSettingValuesObject) {
            $service = $this->_getServiceIssueInstanceBySetting($userSettingValuesObject);
        
            $data = $service->loadRemoteData($date);
        
            foreach ($data as $values)
            {
                $service->create($values);
            }
        }
        
        return true;
    }
    
    public function onCronSyncUsersCommits(DateTime $date): bool
    {
        $userSettings = $this->_getUserSettingsByType(ServiceValuesObject::TYPE_COMMIT);
        
        foreach ($userSettings as $userSettingValuesObject) {
            $service = $this->_getServiceCommitInstanceBySetting($userSettingValuesObject);
        
            $data = $service->loadRemoteData($date);
        
            foreach ($data as $values)
            {
                $service->create($values);
            }
        }
        
        return true;
    }
    
    public function onCronSyncUsersCommitsByService(DateTime $date, IServiceCommit $service): array
    {
        $userSettings = $this->_getUserSettingsByService($service);

        $result = array();
    
        foreach ($userSettings as $userSettingValuesObject) {
            $provider = $this->_createCommitProviderInstance($userSettingValuesObject);
    
            $service->setProvider($provider);
        
            $data = $service->loadRemoteData($date);
        
            foreach ($data as $values)
            {
                $result[] = $service->create($values);
            }
        }
    
        return $result;
    }
    
    public function onCronSyncUsersIssuesByService(DateTime $date, IServiceIssue $service): array
    {
        $userSettings = $this->_getUserSettingsByService($service);

        $result = array();
        
        foreach ($userSettings as $userSettingValuesObject) {
            $provider = $this->_createIssuesProviderInstance($userSettingValuesObject);
    
            $service->setProvider($provider);
            
            $data = $service->loadRemoteData($date);
        
            foreach ($data as $values)
            {
                $result[] = $service->create($values);
            }
        }
        
        return $result;
    }
    
    private function _getServiceCommitInstanceBySetting(
        UserSettingValuesObject $userSettingValuesObject, ?ITrackingDataAccessObject $dao = null
    ): IServiceCommit
    {
        $service = $this->_getServiceByID($userSettingValuesObject->getServiceID());
        
        if (!$service) {
            $msg = __('Could Not Find Service By ID "%s"', $userSettingValuesObject->getServiceID());
            throw new SystemException($msg);
        }
        
        $className = $service->geClassName();
        
        if (!class_exists($className)) {
            $msg = __('Could Not Find Class "%s"', $className);
            throw new SystemException($msg);
        }
        
        $instance = new $className();
    
        if (!$dao) {
            $dao = $this->object;
        }
        
        $instance->onInit($userSettingValuesObject, $dao);
        
        return $instance;
    }
    
    private function _getServiceIssueInstanceBySetting(
        UserSettingValuesObject $userSettingValuesObject, ?ITrackingDataAccessObject $dao = null
    ): IServiceIssue
    {
        $className = $userSettingValuesObject->getClassName();
    
        if (!class_exists($className)) {
            $msg = __('Could Not Find Class "%s"', $className);
            throw new SystemException($msg);
        }
    
        $instance = new $className();
        
        if (!$dao) {
            $dao = $this->object;
        }
        
        $instance->onInit($userSettingValuesObject, $dao);
    
        return $instance;
    }
    
    private function _getUserSettingsByType(string $type): array
    {
        $search = array(
            'tracking_services.type' => $type,
        );
        
        $values = $this->object->searchUserSettings($search);
        
        if ($values) {
            $values = UserSettingValuesObject::convert($values);
        }
        
        return $values;
    }

    private function _getUserSettingsByService(IServiceIssue $service): array
    {
        $search = array(
            'tracking_services.type'     => $service->getType(),
            'tracking_services.platform' => $service->getPlatform(),
        );

        $values = $this->object->searchUserSettings($search);
    
        if (!$values) {
            throw new SystemException('Not Found Users Connected To Service');
        }
    
        return UserSettingValuesObject::convert($values);
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
    
    private function _createIssuesProviderInstance(UserSettingValuesObject $userSettingValuesObject): IProviderIssue
    {
        $className = $userSettingValuesObject->getClassName();
        
        if (!class_exists($className)) {
            throw new SystemException('Calss Provider Not Found '.$className);
        }
        
        return new $className($userSettingValuesObject);
    }
    
    private function _createCommitProviderInstance(UserSettingValuesObject $userSettingValuesObject): IProviderCommit
    {
        $className = $userSettingValuesObject->getClassName();
        
        if (!class_exists($className)) {
            throw new SystemException('Calss Provider Not Found '.$className);
        }
        
        return new $className($userSettingValuesObject);
    }
}