<?php

namespace plugin\tracking;

use plugin\tracking\vo\ITrackingCommitValuesObject;
use plugin\tracking\vo\ITrackingIssueValuesObject;

interface ITrackingDataAccessObject
{
    public function createIssue(ITrackingIssueValuesObject $issueValuesObject): int;
    public function createCommit(ITrackingCommitValuesObject $commitValuesObject): int;
}