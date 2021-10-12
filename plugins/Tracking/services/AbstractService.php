<?php

namespace tracking\services;

use plugin\tracking\ITrackingDataAccessObject;
use plugin\tracking\providers\IProvider;
use plugin\tracking\vo\SettingValuesObject;

abstract class AbstractService
{
    protected IProvider $provider;
    protected ITrackingDataAccessObject $dao;
    
    public function onInit(SettingValuesObject $settings, ITrackingDataAccessObject $dao)
    {
        $this->provider = $this->_createProviderInstance($settings);
        $remoteUserCode = $settings->getRemoteUserCode();
        
        if (!$remoteUserCode) {
            $this->getProvider()->loadRemoteUserID();
        }
        
        $this->dao = $dao;
    }

    public function getProvider(): IProvider
    {
        return $this->provider;
    }
    
    public function setProvider(IProvider $provider): void
    {
        $this->provider = $provider;
    }
    
    private function _createProviderInstance(SettingValuesObject $settings): IProvider
    {
        $className = 'tracking\\providers\\'.ucfirst($settings->getIdent());
        
        return new $className($settings);
    }
}