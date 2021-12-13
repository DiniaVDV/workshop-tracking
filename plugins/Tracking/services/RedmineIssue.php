<?php

namespace tracking\services;

use DateTime;
use plugin\tracking\vo\ITrackingIssueValuesObject;
use plugin\tracking\vo\RedmineIssueValuesObject;

class RedmineIssue extends AbstractServiceIssue implements IServiceIssue
{
    public const TYPE = 'redmine';
    public const PLATFORM = 'gitlab';
    
    /**
     * @return ITrackingIssueValuesObject[]
     */
    public function loadRemoteData(DateTime $date): array
    {
        $issues = $this->getProvider()->getIssues($date);
        
        $this->_preparedIssues($issues);
        
        return $issues;
    }
    
    public function create(ITrackingIssueValuesObject $issueValuesObject): ITrackingIssueValuesObject
    {
        $id = $this->dao->createIssue($issueValuesObject);
    
        $issueValuesObject->setID($id);
    
        return $issueValuesObject;
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