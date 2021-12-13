<?php

namespace plugin\tracking\vo;

class ServiceValuesObject extends \ValuesObject
{
    public const TYPE_ISSUE  = 'issue';
    public const TYPE_COMMIT = 'commit';
    
    public function geClassName()
    {
        return $this->get('class_name');
    }
    
}