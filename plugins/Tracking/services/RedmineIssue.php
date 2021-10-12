<?php

namespace tracking\services;

use plugin\tracking\vo\ITrackingIssueValuesObject;
use plugin\tracking\vo\RedmineIssueValuesObject;

class RedmineIssue extends AbstractService implements IServiceIssue
{
    public const TYPE = 'redmine';
    public const PLATFORM = 'gitlab';
    
    public function loadRemoteData(): array
    {
        $issues = $this->getProvider()->getIssues();
        
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
            $response = $this->getProvider()->getIssueAdditionalInfo($issue);
            
            if (!$response) {
                continue;
            }
    
            $issue['subject'] = $response['issue']['subject'];
            $issue['start_date'] = $response['issue']['start_date'];
            $issue['priority_name'] = $response['priority']['name'];
            $issue['domain'] = $this->getProvider()->getUrl();
    
            $issues[$key] = new RedmineIssueValuesObject($issue);
        }
        
        return true;
    }
    
    public function getType(): string
    {
        return static::TYPE;
    }
    
    public function getPlatform(): string
    {
        return static::PLATFORM;
    }
}