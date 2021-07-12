<?php

namespace tracking\services;

use SettingValuesObject;

class Gitlab implements ITrackingService
{
    protected $settings;
    
    public function __construct(SettingValuesObject $settings)
    {
        $this->settings = $settings;
    }
    
    protected function getSettings(): SettingValuesObject
    {
        return $this->settings;
    }
    
    public function loadUserID():bool
    {
        // TODO move urls to some table
        $url = $this->getSettings()->getUrl().'user';
        
        $response = $this->_sendRemoteRequest($url);
        
        if (!array_key_exists('id', $response)) {
            //TODO Think
            return false;
        }
    
        $this->getSettings()->setRemoteUserCode($response['id']);
        
        return true;
    }
    
    public function getUserProjects()
    {
        $remoteUserCode = $this->getSettings()->getRemoveUserCode();
        
        // TODO move urls to some table
        $url = sprintf(
            '%s/users/%s/projects',
            $this->getSettings()->getUrl(),
            $remoteUserCode
        );
        
        $response = $this->_sendRemoteRequest($url);
        
        if (!is_array(current($response))) {
            //TODO Think
            return null;
        }
        
        return $response;
    }
    
    public function getCommitsByProject(array $project): ?array
    {
        // TODO move urls to some table
        $url = sprintf(
            '%s/projects/%s/repository/commits',
            $this->getSettingVO()->getUrl(),
            $project['id']
        );
        
        $commits = $this->_sendRemoteRequest($url);
        
        if (!is_array(current($commits))) {
            //TODO Think
            return null;
        }
        
        return $commits;
    }
    
    public function getCommitsWithAdditionalData(array $project, array $commits): array
    {
        $data = array();
        
        foreach ($commits as $commit) {
            if ($commit['author_email'] !== $this->getSettings()->getEmail()) {
                continue;
            }
            
            $commitData = $this->_getCommitWithAdditionalData($project, $commit);
            
            if ($commitData) {
                $data[] = new GitlabValuesObject($commitData);
            }
        }
        
        return $data;
    }
    
    private function _getCommitWithAdditionalData(array $project, array $commit): ?array
    {
        // TODO move urls to some table
        $url = sprintf(
            '%s/projects/%s/repository/commits/%s',
            $this->getSettings()->getUrl(),
            $project['id'],
            $commit['id']
        );
        
        $response = $this->_sendRemoteRequest($url);
        
        if (!array_key_exists('id', $response)) {
            //TODO Think
            return null;
        }
        
        $response['project_name'] = $project['name'];
        $response['url_to_project'] = $project['http_url_to_repo'];
        
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