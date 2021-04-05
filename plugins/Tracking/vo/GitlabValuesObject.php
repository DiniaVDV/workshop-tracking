<?php

class GitlabValuesObject extends ValuesObject implements ITrackingValuesObject
{
    public function getID(): string
    {
        return $this->get('id');
    }
    
    public function getHash(): string
    {
        return $this->get('hash');
    }
    
    public function getMessage(): string
    {
        return $this->get('message');
    }
    
    public function getDate(): string
    {
        return $this->get('created_at');
    }
    
    public function getProjectName(): string
    {
        return $this->get('project_name');
    }
    
    public function getProjectID(): string
    {
        return $this->get('project_id');
    }
    
    public function getWebUrl(): string
    {
        return $this->get('url');
    }
    
    public function getUrlToProject(): string
    {
        return $this->get('url_to_project');
    }
    
    public function getCreateValues(): array
    {
        return array(
            'hash'           => $this->getHash(),
            'message'        => $this->getMessage(),
            'date'           => $this->getDate(),
            'project_name'   => $this->getProjectName(),
            'project_id'     => $this->getProjectID(),
            'url'            => $this->getUrl(),
            'url_to_project' => $this->getUrlToProject(),
        );
    }
}