<?php

class GitlabProvider extends AbstractProvider
{
    public function loadRemoteData(): array
    {
        $remoteUserCode = $this->getSettingVO()->getRemoveUserCode();
        
        if (!$remoteUserCode) {
            $remoteUserCode = $this->_getRemoteUserCode();
            $this->getSettingVO()->setRemoteUserCode($remoteUserCode);
        }
    
        return $this->_getCommits();
    }
    
    private function _getCommits(): array
    {
        $projects = $this->_getUserProjects();
    
        $commits = array();
    
        foreach ($projects as $project) {
            $values = $this->_getProjectCommits($project);
            if ($values) {
                $commits[] = $values;
            }
        }
    
        $commits = array_merge(...$commits);
        
        return $commits;
    }
 
    private function _getRemoteUserCode()
    {
        // TODO move urls to some table
        $url = $this->getSettingVO()->getUrl().'user';
    
        $response = $this->_sendRemoteRequest($url);
        
        if (!array_key_exists('id', $response)) {
            //TODO Think
            return null;
        }
        
        $remoteCode = $response['id'];
        
        return $remoteCode;
    }
    
    private function _getUserProjects()
    {
        $remoteUserCode = $this->getSettingVO()->getRemoveUserCode();
    
        // TODO move urls to some table
        $url = Core::getInstance()->getUrl(
            $this->getSettingVO()->getUrl().'users/%s/projects',
            $remoteUserCode
        );
    
        $response = $this->_sendRemoteRequest($url);
    
        if (!is_array(current($response))) {
            //TODO Think
            return null;
        }
        
        return $response;
    }
    
    private function _getProjectCommits(array $project): ?array
    {
        // TODO move urls to some table
        $url = Core::getInstance()->getUrl(
            $this->getSettingVO()->getUrl().'projects/%s/repository/commits',
            $project['id']
        );
    
        $commits = $this->_sendRemoteRequest($url);
    
        if (!is_array(current($commits))) {
            //TODO Think
            return null;
        }
    
        return $this->_getCommitsWithAdditionalData($project, $commits);
    }
    
    private function _getCommitsWithAdditionalData(array $project, array $commits): array
    {
        $data = array();
        
        foreach ($commits as $commit) {
            if ($commit['author_email'] !== $this->getSettingVO()->getEmail()) {
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
        $url = Core::getInstance()->getUrl(
            $this->getSettingVO()->getUrl().'projects/%s/repository/commits/%s',
            $project['id'],
            $commit['id']
        );
    
        $response = $this->_sendRemoteRequest($url);
        
        if (!array_key_exists('id', $response)) {
            //TODO Think
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
                'Authorization: Bearer '.$this->getSettingVO()->getRemoteApiToken(),
            ),
            CURLOPT_FAILONERROR => 0,
        );
    }
}