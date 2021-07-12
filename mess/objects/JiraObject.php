<?php

class JiraObject extends DataAccessObject
{
    const TABLE_MAIN = 'tracking_issues';
    
    public function add(array $values)
    {
        return $this->insert(static::TABLE_MAIN, $values);
    }
}