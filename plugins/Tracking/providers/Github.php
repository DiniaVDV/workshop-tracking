<?php

class GithubProvider extends AbstractProvider
{
    public function loadRemoteData(): array
    {
        $options = array(
        
        );
        
        $curl = new Curl($options);
    
        $result = $curl->getUrl($this->getSettingVO()->getUrl());
        
        $response = $this->_getResponse($result);
    
        return new GithubValuesObject($response);
    }
    
    private function _getResponse($result): array
    {
    
    }
}