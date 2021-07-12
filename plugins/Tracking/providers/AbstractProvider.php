<?php

namespace tracking\providers;

use plugin\tracking\ITrackingDAO;
use plugin\tracking\ITrackingService;
use plugin\tracking\vo\SettingValuesObject;

abstract class AbstractProvider
{
    protected ITrackingService $service;
    protected ITrackingDAO $dao;
    
    public function onInit(SettingValuesObject $settings, ITrackingDAO $dao)
    {
        $this->service = $this->_createServiceInstance($settings);
        $remoteUserCode = $settings->getRemoteUserCode();
        
        if (!$remoteUserCode) {
            $this->getService()->loadUserID();
        }
        
        $this->dao = $dao;
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