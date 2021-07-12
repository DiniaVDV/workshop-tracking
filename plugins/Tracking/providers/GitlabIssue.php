<?php

namespace tracking\providers;

use plugin\tracking\vo\ITrackingIssueValuesObject;

class GitlabIssue extends AbstractProvider implements IProviderIssue
{
    public function loadRemoteData(): array
    {
        $issues = $this->getService()->getIssues();
        
        $this->_getPreparedIssues($issues);
        
        return $issues;
    }
    
    public function create(ITrackingIssueValuesObject $issueValuesObject): array
    {
        return $this->dao->createIssue($issueValuesObject);
    }
    
    
    /**
     * @param array $issues
     * @return \GitlabIssueValuesObject
     */
    private function _getPreparedIssues(array &$issues): array
    {
        $result = array();
        foreach ($issues as $key => $issue) {
        
        }
        
        return $result;
    }
}