<?php

namespace plugin\tracking\providers;

interface IProvider
{
    public function loadRemoteUserID(): ?int;
    
    public function getCommitsByProject(array $project): ?array;
    
    public function getCommitsWithAdditionalData(array $project, array $commits): array;
    
    public function getIssues(): array;
    
    public function getIssueAdditionalInfo(array $issue): ?array;
}