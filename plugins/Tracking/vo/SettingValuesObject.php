<?php

namespace plugin\tracking\vo;

class SettingValuesObject extends ValuesObject
{
    public function getUrl()
    {
        return $this->get('url');
    }
    
    public function getToken()
    {
        return $this->get('token');
    }

    public function getServiceID()
    {
        return $this->get('id_service');
    }
}