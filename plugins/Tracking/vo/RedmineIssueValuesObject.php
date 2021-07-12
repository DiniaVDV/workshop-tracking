<?php

namespace plugin\tracking\vo;

class RedmineIssueValuesObject extends \ValuesObject implements ITrackingIssueValuesObject
{
    public function getID(): int
    {
        $issue = $this->_getIssue();
        
        return $issue['id'];
    }
    
    public function getStartDate(): string
    {
        return $this->get('start_date');
    }
    
    public function getSpentOn(): string
    {
        return $this->get('spent_on');
    }
    
    public function getProjectName(): string
    {
        $project = $this->_getProject();
    
        return $project['name'];
    }
    
    public function getProjectID(): string
    {
        $project = $this->_getProject();
        
        return $project['id'];
    }
    
    public function getPriorityName(): string
    {
        return $this->get('priority_name');
    }
    
    public function getCommets(): string
    {
        return $this->get('comments');
    }
    
    public function getHour(): string
    {
        return $this->get('hours');
    }
    
    public function getSubject(): string
    {
        return $this->get('subject');
    }
    
    public function getDomain(): string
    {
        return $this->get('domain');
    }
    
    public function getUrl(): string
    {
        return sprintf('%s/issues/%s', $this->getDomain(), $this->getID());
    }
    
    private function _getProject()
    {
        return $this->get('project');
    }
    
    private function _getIssue()
    {
        return $this->get('issue');
    }
}