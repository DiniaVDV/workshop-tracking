<?php

require_once 'UserValuesObject.php';

class UsersPlugin extends ObjectPlugin
{
    public function search(array $search = array()): array
    {
        $values = $this->object->search($search);
        
        if ($values) {
            $values = $this->_convertDataToValuesObject($values);
        }
        
        return $values;
    }
    
    private function _convertDataToValuesObject(array $values): array
    {
        $vos = array();
        
        foreach ($values as $row) {
            $vos[] = new UserValuesObject($row);
        }
        
        return $vos;
    }
}