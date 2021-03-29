<?php


abstract class AbstractProvider implements IProvider
{
    protected $object;
    protected $settingVO;
    
    public function __construct(SettingValuesObject $setting)
    {
        $objectName = $this->_getObjectName();
        $objectPath = $this->_getObjectPath();
        
        $this->object = Core::getInstance()->getObject(
            $objectName,
            false,
            $objectPath
        );
        $this->settingVO = $setting;
    }
    
    public function create(ValuesObject $valuesObject): int
    {
        $values = $valuesObject->getCreateValues();
        
        return $this->object->add($values);
    }
    
    protected function getSettingVO(): SettingValuesObject
    {
        return $this->settingVO;
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