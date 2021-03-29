<?php

class GithubProvider extends AbstractProvider
{
    public function loadRemoteData(ValuesObject $valuesObject): array
    {
        $options = array(
        
        );
        
        $curl = new Curl($options);
    
        $result = $curl->getUrl($valuesObject->getUrl());
        
        $response = $this->_getResponse($result);
    
        return new GithubValuesObject($response);
    }
    
    private function _getResponse($result): array
    {
    
    }
}