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
    
    public function searchUserSettings(array $search = array())
    {
        $sql = "SELECT
                        tracking_user_settings.*,
                        tracking_services.class_name
                FROM ".static::TABLE_USER_SETTINGS."
                LEFT JOIN ".static::TABLE_SERVICE."
                     ON tracking_user_settings.id_service = tracking_services.id";
    
        return $this->select($sql, $search);
    }
    
    public function getServiceByID(int $id)
    {
        $sql = "SELECT * FROM ".static::TABLE_SERVICE;
        $search = array(
            'id' => $id,
        );
        
        return $this->select($sql, $search, array(), static::FETCH_ROW);
    }
    
    public function getService(array $search)
    {
        $sql = "SELECT * FROM ".static::TABLE_SERVICE;
    
        return $this->select($sql, $search, array(), static::FETCH_ROW);
    }
    
    public function createIssue(ITrackingIssueValuesObject $issueValuesObject): int
    {
        return $this->insert(static::TABLE_ISSUES, $issueValuesObject->getValues());
    }
    
    public function createCommit(ITrackingCommitValuesObject $commitValuesObject): int
    {
        return $this->insert(static::TABLE_COMMITS, $commitValuesObject->getValues());
    }
}