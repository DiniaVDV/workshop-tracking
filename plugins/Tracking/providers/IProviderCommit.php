<?php

namespace tracking\providers;

use plugin\tracking\ITrackingService;
use plugin\tracking\vo\ITrackingCommitValuesObject;

interface IProviderCommit
{
    public const TYPE = 'commit';
    
    public function loadRemoteData(): array;
    public function setService(ITrackingService $service): void;
    public function create(ITrackingCommitValuesObject $commitValuesObject): array;
}