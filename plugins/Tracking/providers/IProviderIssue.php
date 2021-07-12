<?php

namespace tracking\providers;

use plugin\tracking\ITrackingService;
use plugin\tracking\vo\ITrackingIssueValuesObject;

interface IProviderIssue
{
    public const TYPE = 'issue';
    
    public function loadRemoteData(): array;
    public function setService(ITrackingService $service): void;
    public function create(ITrackingIssueValuesObject $issueValuesObject): array;
}