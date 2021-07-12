<?php

namespace tracking\providers;

use plugin\tracking\ITrackingDAO;
use plugin\tracking\vo\ITrackingCommitValuesObject;
use plugin\tracking\vo\SettingValuesObject;

interface IProviderCommit
{
    public function onInit(SettingValuesObject $settings, ITrackingDAO $object);
    public function loadRemoteData(): array;
    public function create(ITrackingCommitValuesObject $commitValuesObject): array;
}