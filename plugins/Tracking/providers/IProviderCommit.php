<?php

namespace tracking\providers;

use plugin\tracking\ITrackingDataAccessObject;
use plugin\tracking\vo\ITrackingCommitValuesObject;
use plugin\tracking\vo\SettingValuesObject;

interface IProviderCommit
{
    public function onInit(SettingValuesObject $settings, ITrackingDataAccessObject $object);
    public function loadRemoteData(): array;
    public function create(ITrackingCommitValuesObject $commitValuesObject): array;
}