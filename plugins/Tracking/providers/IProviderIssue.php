<?php

namespace plugin\tracking\providers;

use DateTime;

interface IProviderIssue
{
    public function loadRemoteUserID(): ?int;
    
    public function getIssues(DateTime $date): array;
    
    public function getIssueAdditionalInfo(array $issue): ?array;
}