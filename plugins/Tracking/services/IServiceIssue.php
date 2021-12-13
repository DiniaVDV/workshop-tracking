<?php

namespace tracking\services;

use DateTime;
use plugin\tracking\vo\ITrackingIssueValuesObject;

interface IServiceIssue
{
    public function loadRemoteData(DateTime $date): array;

    public function create(ITrackingIssueValuesObject $issueValuesObject): ITrackingIssueValuesObject;
    
    public function getType(): string;
    
    public function getPlatform(): string;
}