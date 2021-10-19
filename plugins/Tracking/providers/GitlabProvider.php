<?php

namespace plugin\tracking\providers;

use plugin\tracking\vo\GithubCommitValuesObject;
use plugin\tracking\vo\SettingValuesObject;

class GitlabProvider implements IProvider
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
    
    public function loadRemoteUserID(): ?int
    {
        // TODO move urls to some table
        $url = $this->getSettings()->getUrl().'user';
        
        $response = $this->_sendRemoteRequest($url);
        
        if (!array_key_exists('id', $response)) {
            //TODO Think
            return null;
        }
    
        $this->getSettings()->setRemoteUserCode($response['id']);
        
        return $response['id'];
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
    
    /**
     * @param array $project
     * @param array $commits
     * @return GithubCommitValuesObject[]
     */
    public function getCommitsWithAdditionalData(array $project, array $commits): array
    {
        $values = array();
        
        foreach ($commits as $commit) {
            if ($commit['author_email'] !== $this->getSettings()->getEmail()) {
                continue;
            }
            
            $commitData = $this->_getCommitWithAdditionalData($project, $commit);
            
            if ($commitData) {
                $values[] = new GitlabValuesObject($commitData);
            }
        }
        
        return $values;
    }
    
    public function getIssues(): array
    {
        // TODO: Implement getIssues() method.
    }
    
    public function getIssueAdditionalInfo(array $issue): ?array
    {
        // TODO: Implement getIssueAdditionalInfo() method.
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