<?php

namespace tracking\providers;

class RedmineIssue extends AbstractProvider
{
    public function loadRemoteData(): array
    {
        $issues = $this->getService()->getIssues();
        
        $this->_preparedIssues($issues);
        
        return $issues;
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