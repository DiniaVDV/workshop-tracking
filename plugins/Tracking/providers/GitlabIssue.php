<?php

namespace tracking\providers;

class GitlabIssue
{
    public function loadRemoteData(): array
    {
        $issues = $this->getService()->getIssues();
        
        $this->_getPreparedIssues($issues);
        
        return $issues;
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