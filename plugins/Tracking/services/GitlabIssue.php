<?php

namespace tracking\services;

use DateTime;
use plugin\tracking\vo\GitlabIssueValuesObject;
use plugin\tracking\vo\ITrackingIssueValuesObject;

class GitlabIssue extends AbstractServiceIssue implements IServiceIssue
{
    public const TYPE = 'issue';
    public const PLATFORM = 'gitlab';
    
    public function loadRemoteData(DateTime $date): array
    {
        $issues = $this->getProvider()->getIssues($date);
        
        $this->_getPreparedIssues($issues);
        
        return $issues;
    }
    
    public function create(ITrackingIssueValuesObject $issueValuesObject): ITrackingIssueValuesObject
    {
        $id = $this->dao->createIssue($issueValuesObject);
    
        $issueValuesObject->setID($id);
    
        return $issueValuesObject;
    }
    
    /**
     * @param array $issues
     * @return GitlabIssueValuesObject[]
     */
    private function _getPreparedIssues(array &$issues): array
    {
        $result = array();
        foreach ($issues as $key => $issue) {
        
        }
        
        return $result;
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