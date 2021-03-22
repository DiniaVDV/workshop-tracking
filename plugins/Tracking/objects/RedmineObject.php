<?php

class RedmineObject extends DataAccessObject
{
    const TABLE_MAIN = 'tracking_time';
    
    public function add(array $values)
    {
        return $this->insert(static::TABLE_MAIN, $values);
    }
}