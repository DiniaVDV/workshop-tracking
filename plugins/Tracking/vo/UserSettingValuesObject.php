<?php

namespace plugin\tracking\vo;

use ValuesObject;

class UserSettingValuesObject extends ValuesObject
{
    public function getUrl(): string
    {
        return $this->get('url');
    }
    
    public function getToken(): string
    {
        return $this->get('token');
    }

    public function getServiceID(): int
    {
        return $this->get('id_service');
    }
    
    public function getRemoteUserCode(): string
    {
        return $this->get('remote_user_code');
    }
    
    public function getClassName(): string
    {
        return $this->get('class_name');
    }
}