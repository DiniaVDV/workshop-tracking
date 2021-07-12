<?php

namespace tracking\providers;

use plugin\tracking\ITrackingDataAccessObject;
use plugin\tracking\vo\ITrackingIssueValuesObject;
use plugin\tracking\vo\SettingValuesObject;

interface IProviderIssue
{
    public function onInit(SettingValuesObject $settings, ITrackingDataAccessObject $object);
    public function loadRemoteData(): array;
    public function create(ITrackingIssueValuesObject $issueValuesObject): array;
}