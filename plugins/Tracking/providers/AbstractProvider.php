<?php


abstract class AbstractProvider implements IProvider
{
    protected $object;
    
    public function __construct()
    {
        $objectName = $this->_getObjectName();
        $objectPath = $this->_getObjectPath();
        
        $this->object = Core::getInstance()->getObject(
            $objectName,
            false,
            $objectPath
        );
    }
    
    public function create(ValuesObject $valuesObject): int
    {
        $values = $valuesObject->getCreateValues();
        
        return $this->object->add($values);
    }
    
    private function _getObjectName(): string
    {
        $className = get_class($this);
    
        return strstr($className, 'Provider', true);
    }
    
    private function _getObjectPath(): string
    {
        return realpath(__DIR__.'../objects/');
    }
}