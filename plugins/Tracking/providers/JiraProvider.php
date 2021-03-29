<?php

class JiraProvider extends AbstractProvider
{
    public function loadRemoteData(ValuesObject $valuesObject): array
    {
    
        $options = array(
    
        );
    
        $curl = new Curl($options);
    
        $result = $curl->getUrl($valuesObject->getUrl());
    
        $response = $this->_getResponse($result);
    
        return new JiraValuesObject($response);
    }
    
    private function _getResponse($result): array
    {
    
    }
}