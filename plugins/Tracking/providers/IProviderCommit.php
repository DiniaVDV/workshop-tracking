<?php

namespace plugin\tracking\providers;

use DateTime;

interface IProviderCommit
{
    public function loadRemoteUserID(): ?int;
    
    public function getCommitsByProject(array $project): ?array;
    
    public function getCommits(DateTime $date): array;
    
    public function getCommitsWithAdditionalData(array $project, array $commits): array;
    
}