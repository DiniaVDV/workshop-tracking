<?php

namespace tracking\services;

use DateTime;
use plugin\tracking\vo\GitlabCommitValuesObject;
use plugin\tracking\vo\ITrackingCommitValuesObject;

class GitlabCommit extends AbstractServiceCommit implements IServiceCommit
{
    public const TYPE = 'commit';
    public const PLATFORM = 'gitlab';
    
    /**
     * @return GitlabCommitValuesObject[]
     */
    public function loadRemoteData(DateTime $date): array
    {
        return $this->getProvider()->getCommits($date);
    }
    
    public function create(ITrackingCommitValuesObject $commitValuesObject): ITrackingCommitValuesObject
    {
        $id = $this->dao->createCommit($commitValuesObject);
        
        $commitValuesObject->setID($id);
        
        return $commitValuesObject;
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