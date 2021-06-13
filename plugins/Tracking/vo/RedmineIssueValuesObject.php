<?php


class RedmineCommitValuesObject extends ValuesObject implements ITrackingIssueValuesObject
{
    public function getID(): int
    {
        return $this->get('id');
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
        return $this->get('project_name');
    }
    
    public function getProjectID(): string
    {
        return $this->get('project_id');
    }
    
    public function getPriorityName(): string
    {
        return $this->get('priority_name');
    }
    
    public function getUrl(): string
    {
        return $this->get('url');
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
    
    public function getCreateValues(): array
    {
        return array(
            'subject'       => $this->getSubject(),
            'hours'         => $this->getHour(),
            'comments'      => $this->getCommets(),
            'start_date'    => $this->getStartDate(),
            'spent_on'      => $this->getSpentOn(),
            'project_name'  => $this->getProjectName(),
            'project_id'    => $this->getProjectID(),
            'priority_name' => $this->getPriorityName(),
            'url'           => $this->getUrl(),
        );
    }
}