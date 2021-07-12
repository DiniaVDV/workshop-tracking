<?php

namespace tracking\providers;

use plugin\tracking\vo\GitlabCommitValuesObject;
use plugin\tracking\vo\ITrackingCommitValuesObject;

class GitlabCommit extends AbstractProvider implements IProviderCommit
{
    /**
     * @return GitlabCommitValuesObject[]
     */
    public function loadRemoteData(): array
    {
        $projects = $this->getService()->getUserProjects();
        $commits  = array();
        
        foreach ($projects as $project) {
            $values = $this->getService()->getCommitsByProject($project);
            
            if ($values) {
                $values = $this->getService()->getCommitsWithAdditionalData($project, $values);
                $commits[] = $values;
            }
        }
    
        return array_merge(...$commits);
    }
    
    public function create(ITrackingCommitValuesObject $commitValuesObject): array
    {
        return $this->dao->createCommit($commitValuesObject);
    }
}