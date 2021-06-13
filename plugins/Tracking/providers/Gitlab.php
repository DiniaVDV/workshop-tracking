<?php

namespace tracking\providers;

class Gitlab extends AbstractProvider
{
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
}