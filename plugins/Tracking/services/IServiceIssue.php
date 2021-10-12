<?php

namespace tracking\services;

use plugin\tracking\providers\IProvider;
use plugin\tracking\vo\ITrackingIssueValuesObject;

interface IServiceIssue
{
    public function loadRemoteData(): array;
    
    public function getProvider(): IProvider;
    
    public function setProvider(IProvider $provider): void;
    
    public function create(ITrackingIssueValuesObject $issueValuesObject): array;
    
    public function getType(): string;
    
    public function getPlatform(): string;
}