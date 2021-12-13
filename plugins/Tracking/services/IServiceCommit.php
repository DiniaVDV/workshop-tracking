<?php

namespace tracking\services;

use DateTime;
use plugin\tracking\vo\ITrackingCommitValuesObject;

interface IServiceCommit
{
    public function loadRemoteData(DateTime $date): array;

    public function create(ITrackingCommitValuesObject $commitValuesObject): ITrackingCommitValuesObject;
    
    public function getType(): string;
    
    public function getPlatform(): string;
}