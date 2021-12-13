<?php

namespace tracking\services;

use plugin\tracking\ITrackingDataAccessObject;
use plugin\tracking\providers\IProviderIssue;
use plugin\tracking\vo\ServiceValuesObject;
use plugin\tracking\vo\UserSettingValuesObject;
use SystemException;

class AbstractServiceIssue
{
    protected IProviderIssue $provider;
    protected ITrackingDataAccessObject $dao;
    
    public function onInit(
        UserSettingValuesObject $userSettingValuesObject,
        ITrackingDataAccessObject $dao
    )
    {
        $provider = $this->_createProviderInstance($userSettingValuesObject);
    
        $this->setProvider($provider);
        
        $remoteUserCode = $userSettingValuesObject->getRemoteUserCode();
        
        if (!$remoteUserCode) {
            $this->getProvider()->loadRemoteUserID();
        }
        
        $this->dao = $dao;
    }
    
    public function getProvider(): IProviderIssue
    {
        return $this->provider;
    }
    
    public function setProvider(IProviderIssue $provider): void
    {
        $this->provider = $provider;
    }
    
    private function _createProviderInstance(UserSettingValuesObject $userSettingValuesObject): IProviderIssue
    {
        $className = $userSettingValuesObject->getClassName();
    
        if (!class_exists($className)) {
            $msg = __('Could Not Find Class "%s"', $className);
            throw new SystemException($msg);
        }
        
        return new $className($userSettingValuesObject);
    }
}