<?php

namespace tracking\services;

use plugin\tracking\vo\ITrackingIssueValuesObject;

class GitlabIssue extends AbstractService implements IServiceIssue
{
    public const TYPE = 'issue';
    public const PLATFORM = 'gitlab';
    
    public function loadRemoteData(): array
    {
        $issues = $this->getProvider()->getIssues();
        
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
    
    public function getType(): string
    {
        return static::TYPE;
    }
    
    public function getPlatform(): string
    {
        return static::PLATFORM;
    }
}