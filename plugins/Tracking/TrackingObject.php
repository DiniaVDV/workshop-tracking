<?php

class TrackingObject extends DataAccessObject
{
    public const TABLE_USER_SETTINGS = 'tracking_user_settings';
    public const TABLE_PLATFORMS     = 'tracking_platforms';
    
    public function searchSettings(array $search = array())
    {
        $sql = "SELECT * FROM ".static::TABLE_USER_SETTINGS;
    
        return $this->select($sql, $search);
    }
}