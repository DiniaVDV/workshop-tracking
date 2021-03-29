<?php

class JiraProvider extends AbstractProvider
{
    public function loadRemoteData(): array
    {
    
        $options = array(
    
        );
    
        $curl = new Curl($options);
    
        $result = $curl->getUrl($this->getSettingVO()->getUrl());
    
        $response = $this->_getResponse($result);
    
        return new JiraValuesObject($response);
    }
    
    private function _getResponse($result): array
    {
    
    }
}