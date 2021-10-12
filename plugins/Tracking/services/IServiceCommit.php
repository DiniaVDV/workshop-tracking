<?php

namespace tracking\services;

use plugin\tracking\providers\IProvider;
use plugin\tracking\vo\ITrackingCommitValuesObject;

interface IServiceCommit
{
    public function loadRemoteData(): array;
    
    public function getProvider(): IProvider;
    
    public function setProvider(IProvider $provider): void;
    
    public function create(ITrackingCommitValuesObject $commitValuesObject): array;
    
    public function getType(): string;
    
    public function getPlatform(): string;
}