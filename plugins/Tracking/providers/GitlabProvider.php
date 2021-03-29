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
    
    private function _getCommit(array $project, array $commit): ?array
    {
        $options = $this->_getDefaultCurlOptions();
    
        $curl = new Curl($options);
    
        $url = Core::getInstance()->getUrl(
            $this->getSettingVO()->getUrl().'projects/%s/repository/commits/%s',
            $project['id'],
            $commit['id']
        );
    
    
        $result = $curl->getUrl($url, false, false, false, true);
    
        $response = json_decode($result, true);
    
        if (!$response || !is_array($response) || !array_key_exists('id', $response)) {
            //TODO Think
            return null;
        }
    
        return $response;
    }
    
    private function _getRemoteUserCode()
    {
        $options = $this->_getDefaultCurlOptions();
    
        $curl = new Curl($options);
    
        $url = $this->getSettingVO()->getUrl().'user';
        
        $result = $curl->getUrl($url, false, false, false, true);
        
        $response = json_decode($result, true);
        
        if (!$response || !is_array($response) || !array_key_exists('id', $response)) {
            //TODO Think
            return null;
        }
        
        $remoteCode = $response['id'];
        
        return $remoteCode;
    }
    
    private function _getUserProjects()
    {
        $remoteUserCode = $this->getSettingVO()->getRemoveUserCode();
        
        $url = Core::getInstance()->getUrl(
            $this->getSettingVO()->getUrl().'users/%s/projects',
            $remoteUserCode
        );
        
        $options = $this->_getDefaultCurlOptions();
    
        $curl = new Curl($options);
    
        $result = $curl->getUrl($url, false, false, false, true);
    
        $response = json_decode($result, true);
    
        if (!$response || !is_array($response) || !is_array(current($response))) {
            //TODO Think
            return null;
        }
        
        return $response;
    }
    
    private function _getProjectCommits(array $project): ?array
    {
        $url = Core::getInstance()->getUrl(
            $this->getSettingVO()->getUrl().'projects/%s/repository/commits',
            $project['id']
        );
    
        $options = $this->_getDefaultCurlOptions();
    
        $curl = new Curl($options);
    
        $result = $curl->getUrl($url, false, false, false, true);
    
        $commits = json_decode($result, true);
    
        if (!$commits || !is_array($commits) || !is_array(current($commits))) {
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
    
            $commitData = $this->_getCommit($project, $commit);
    
            if ($commitData) {
                $data[] = new GitlabValuesObject($commitData);
            }
        }
        
        return $data;
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