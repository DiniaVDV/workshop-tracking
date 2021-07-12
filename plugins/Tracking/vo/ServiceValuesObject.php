<?php

namespace plugin\tracking\vo;

class ServiceValuesObject extends ValuesObject
{
    public function geClassName()
    {
        return $this->get('class_name');
    }
    
}