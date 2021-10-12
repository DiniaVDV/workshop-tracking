<?php

namespace plugin\tracking;

use plugin\tracking\vo\ITrackingCommitValuesObject;
use plugin\tracking\vo\ITrackingIssueValuesObject;

class TrackingObject extends \DataAccessObject implements ITrackingDataAccessObject
{
    public const TABLE_USER_SETTINGS = 'tracking_user_settings';
    public const TABLE_SERVICE       = 'tracking_services';
    public const TABLE_ISSUES        = 'tracking_issues';
    public const TABLE_COMMITS       = 'tracking_commits';
    
    public function searchSettings(array $search = array())
    {
        $sql = "SELECT * FROM ".static::TABLE_USER_SETTINGS;
    
        return $this->select($sql, $search);
    }
    
    public function getServiceByID(int $id)
    {
        $sql = "SELECT * FROM ".static::TABLE_USER_SETTINGS;
        $search = array(
            'id' => $id,
        );
        
        return $this->select($sql, $search, array(), static::FETCH_ROW);
    }
    
    public function getService(array $search)
    {
        $sql = "SELECT * FROM ".static::TABLE_USER_SETTINGS;
    
        return $this->select($sql, $search, array(), static::FETCH_ROW);
    }
    
    public function createIssue(ITrackingIssueValuesObject $issueValuesObject)
    {
        return $this->insert(static::TABLE_ISSUES, $issueValuesObject->getValues());
    }
    
    public function createCommit(ITrackingCommitValuesObject $commitValuesObject)
    {
        return $this->insert(static::TABLE_COMMITS, $commitValuesObject->getValues());
    }
}