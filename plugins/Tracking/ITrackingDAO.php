<?php

namespace plugin\tracking;

use plugin\tracking\vo\ITrackingCommitValuesObject;
use plugin\tracking\vo\ITrackingIssueValuesObject;

interface ITrackingDAO
{
    public function createIssue(ITrackingIssueValuesObject $issueValuesObject);
    public function createCommit(ITrackingCommitValuesObject $commitValuesObject);
}