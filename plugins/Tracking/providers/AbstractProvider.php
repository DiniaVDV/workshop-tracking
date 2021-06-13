<?php

namespace tracking\providers;

abstract class AbstractProvider implements IProvider
{
    protected $service;
    
    public function onInit(SettingValuesObject $settings, ITrackingObject $object)
    {
        $this->service = $this->_createServiceInstance($settings);
        $remoteUserCode = $settings->getRemoteUserCode();
        
        if (!$remoteUserCode) {
            $this->getService()->loadUserID();
        }
    }

    public function getService(): ITrackingService
    {
        return $this->service;
    }
    
    private function _createServiceInstance(SettingValuesObject $settings): ITrackingService
    {
        $className = 'tracking\\libs\\'.ucfirst($settings->getIdent());
        
        return new $className($settings);
    }
}