<?php

class GitlabProvider extends AbstractProvider
{
    public function loadRemoteData(ValuesObject $valuesObject): array
    {
        $remoteUserCode = $valuesObject->getRemoveUserCode();
        
        if (!$remoteUserCode) {
            $remoteUserCode = $this->_getRemoteUserCode($valuesObject);
            $valuesObject->setRemoteUserCode($remoteUserCode);
        }
    
        return $this->_getCommits($valuesObject);
    }
    
    private function _getCommits(ValuesObject $valuesObject): array
    {
        $projects = $this->_getUserProjects($valuesObject);
    
        $commits = array();
    
        foreach ($projects as $project) {
            $values = $this->_getProjectCommits($valuesObject, $project);
            if ($values) {
                $commits[] = $values;
            }
        }
    
        $commits = array_merge(...$commits);
        
        return $this->_getPreparedCommits($valuesObject, $commits);
    }

    private function _getPreparedCommits(ValuesObject &$valuesObject, array $commits): array
    {
        $data = array();
        
        foreach ($commits as $key => $commit) {
            if ($commit['author_email'] !== $valuesObject->getEmail()) {
                unset($commits[$key]);
                continue;
            }
    
            $data[] = new GitlabValuesObject($commit);
        }
        
        return $data;
    }
    
    private function _getRemoteUserCode(ValuesObject &$valuesObject)
    {
        $options = $this->_getDefaultCurlOptions($valuesObject);
    
        $curl = new Curl($options);
    
        $url = $valuesObject->getUrl().'user';
        
        $result = $curl->getUrl($url, false, false, false, true);
        
        $response = json_decode($result, true);
        
        if (!$response || !is_array($response) || !array_key_exists('id', $response)) {
            //TODO Think
            return null;
        }
        
        $remoteCode = $response['id'];
        
        return $response['id'];
    }
    
    private function _getUserProjects(ValuesObject $valuesObject)
    {
        $remoteUserCode = $valuesObject->getRemoveUserCode();
        
        $url = Core::getInstance()->getUrl(
            $valuesObject->getUrl().'users/%s/projects',
            $remoteUserCode
        );
        
        $options = $this->_getDefaultCurlOptions($valuesObject);
    
        $curl = new Curl($options);
    
        $result = $curl->getUrl($url, false, false, false, true);
    
        $response = json_decode($result, true);
    
        if (!$response || !is_array($response) || !is_array(current($response))) {
            //TODO Think
            return null;
        }
        
        return $response;
    }
    
    private function _getProjectCommits(ValuesObject $valuesObject, $project): array
    {
        $url = Core::getInstance()->getUrl(
            $valuesObject->getUrl().'projects/%s/repository/commits',
            $project['id']
        );
    
        $options = $this->_getDefaultCurlOptions($valuesObject);
    
        $curl = new Curl($options);
    
        $result = $curl->getUrl($url, false, false, false, true);
    
        $response = json_decode($result, true);
    
        if (!$response || !is_array($response) || !is_array(current($response))) {
            //TODO Think
            return null;
        }
    
        return $response;
    }
    
    private function _getDefaultCurlOptions(ValuesObject $valuesObject): array
    {
        return array(
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$valuesObject->getRemoteApiToken(),
            ),
            CURLOPT_FAILONERROR => 0,
        );
    }
}