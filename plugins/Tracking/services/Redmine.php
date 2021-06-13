<?php

namespace tracking\services;

class Redmine implements \ITrackingService
{
    public function loadUserID(): bool
    {
        // TODO move urls to some table
        $url = $this->getSettings()->getUrl().'/users/current.json';
    
        $response = $this->_sendRemoteRequest($url);
    
        if (!array_key_exists('user', $response) || !is_array($response['user'])) {
            //TODO Think
            return false;
        }
    
        $this->getSettings()->setRemoteUserCode($response['id']);
        
        return true;
    }
    
    public function getIssues(): array
    {
        $offset = 0;
        $limit  = 50;
        $issues = array();
        
        do {
            $params = array(
                'user_id'  => $this->getSettings()->getRemoteUserCode(),
                'spent_on' => date('Y-m-d', strtotime('- 1 day')),
                'offset'   => $offset,
                'limit'    => $limit,
            );
            $url = sprintf(
                '%stime_entries.json?%s',
                $this->getSettings()->getUrl(),
                http_build_query($params)
            );
            
            $response = $this->_sendRemoteRequest($url);
            
            if (!array_key_exists('time_entries', $response)) {
                break;
            }
            $issues[] = $response;
            $offset += $limit;
        } while ($response['time_entries']);
 
        return array_merge(...$issues);
    }
    
    public function getIssueAdditionalInfo(array $issue): ?array
    {
        if (!array_key_exists('issue', $issue) ||
            !array_key_exists('id', $issue['issue'])) {
            return null;
        }
    
        $url = sprintf(
            '%s/issues/%s.json',
            $this->getSettings()->getUrl(),
            $issue['issue']['id']
        );
    
        $response = $this->_sendRemoteRequest($url);
    
        if (!array_key_exists('issue', $response) ||
            !is_array($response['issue'])) {
            return null;
        }
        
        return $response;
    }

    private function _sendRemoteRequest(string $url): array
    {
        $options = $this->_getDefaultCurlOptions();
        
        $curl = new Curl($options);
        
        $result = $curl->getUrl($url, false, false, false, true);
        
        $response = json_decode($result, true);
        
        if (!$response || !is_array($response) || !array_key_exists('id', $response)) {
            //TODO Think
            return array();
        }
        
        return $response;
    }
    
    private function _getDefaultCurlOptions(): array
    {
        return array(
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->getSettings()->getRemoteApiToken(),
            ),
            CURLOPT_FAILONERROR => 0,
        );
    }
}