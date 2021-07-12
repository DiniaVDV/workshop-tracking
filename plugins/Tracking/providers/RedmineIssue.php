<?php

namespace tracking\providers;

use plugin\tracking\vo\ITrackingIssueValuesObject;

class RedmineIssue extends AbstractProvider implements IProviderIssue
{
    public function loadRemoteData(): array
    {
        $issues = $this->getService()->getIssues();
        
        $this->_preparedIssues($issues);
        
        return $issues;
    }
    
    public function create(ITrackingIssueValuesObject $commitValuesObject): array
    {
        return $this->dao->createIssue($commitValuesObject);
    }
    
    private function _preparedIssues(array &$issues): bool
    {
        foreach ($issues as $key => $issue) {
            $response = $this->getService()->getIssueAdditionalInfo($issue);
            
            if (!$response) {
                continue;
            }
    
            $issue['subject'] = $response['issue']['subject'];
            $issue['start_date'] = $response['issue']['start_date'];
            $issue['priority_name'] = $response['priority']['name'];
            $issue['domain'] = $this->getService()->getSettings()->getUrl();
    
            $issues[$key] = new RedmineIssueValuesObject($issue);
        }
        
        return true;
    }
}