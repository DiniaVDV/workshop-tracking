<?php

class RedmineProvider extends AbstractProvider
{
    public function loadRemoteData(): array
    {
        $remoteUserCode = $this->getSettingVO()->getRemoteUserCode();
        
        if (!$remoteUserCode) {
            $remoteUserCode = $this->_getRemoteUserCode();
            $this->getSettingVO()->setRemoteUserCode($remoteUserCode);
        }
        
        return $this->_getTasks();
    }
    
    private function _getTasks(): array
    {
        $offset = 0;
        $limit  = 50;
        $tasks = array();
        
        do {
            $params = array(
                'user_id'  => $this->getSettingVO()->getRemoteUserCode(),
                'spent_on' => date('Y-m-d', strtotime('- 1 day')),
                'offset'   => $offset,
                'limit'    => $limit,
            );
            $url = Core::getInstance()->getUrl(
                $this->getSettingVO()->getUrl().'time_entries.json',
                $params
            );
    
            $response = $this->_sendRemoteRequest($url);
            
            if (!array_key_exists('time_entries', $response)) {
                break;
            }
            $tasks[] = $response;
            $offset += $limit;
        } while ($response['time_entries']);
    
        $tasks = array_merge(...$tasks);
    
        if ($tasks) {
            $this->_preparedTasks($tasks);
        }
        
        return $tasks;
    }
    
    private function _preparedTasks(array &$tasks): bool
    {
        foreach ($tasks as &$task) {
            if (!array_key_exists('issue', $task) ||
                !array_key_exists('id', $task['issue'])) {
                continue;
            }
            $url = Core::getInstance()->getUrl(
                $this->getSettingVO()->getUrl().'issues/%s.json',
                $task['issue']['id']
            );
    
            $response = $this->_sendRemoteRequest($url);
    
            $task['subject'] = $response['issue']['subject'];
            $task['start_date'] = $response['issue']['start_date'];
            $task['subject'] = $response['issue']['subject'];
        }
        
        return true;
    }
    
    private function _getRemoteUserCode()
    {
        // TODO move urls to some table
        $url = $this->getSettingVO()->getUrl().'/users/current.json';
        
        $response = $this->_sendRemoteRequest($url);
        
        if (!array_key_exists('user', $response) || !is_array($response['user'])) {
            //TODO Think
            return null;
        }
        
        return $response['id'];
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
                'Authorization: Bearer '.$this->getSettingVO()->getRemoteApiToken(),
            ),
            CURLOPT_FAILONERROR => 0,
        );
    }
   
}