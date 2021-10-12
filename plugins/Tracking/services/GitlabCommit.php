<?php

namespace tracking\services;

use plugin\tracking\vo\GitlabCommitValuesObject;
use plugin\tracking\vo\ITrackingCommitValuesObject;

class GitlabCommit extends AbstractService implements IServiceCommit
{
    public const TYPE = 'commit';
    public const PLATFORM = 'gitlab';
    
    /**
     * @return GitlabCommitValuesObject[]
     */
    public function loadRemoteData(): array
    {
        $projects = $this->getProvider()->getUserProjects();
        $commits  = array();
        
        foreach ($projects as $project) {
            $values = $this->getProvider()->getCommitsByProject($project);
            
            if ($values) {
                $values = $this->getProvider()->getCommitsWithAdditionalData($project, $values);
                $commits[] = $values;
            }
        }
    
        return array_merge(...$commits);
    }
    
    public function create(ITrackingCommitValuesObject $commitValuesObject): array
    {
        return $this->dao->createCommit($commitValuesObject);
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