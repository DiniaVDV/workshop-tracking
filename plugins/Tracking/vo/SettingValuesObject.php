<?php

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
    
    public function geClassName()
    {
        return $this->get('class_name');
    }
}