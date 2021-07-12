<?php

class GitlabObject extends DataAccessObject
{
    const TABLE_MAIN = 'tracking_commits';
    
    public function add(array $values)
    {
        return $this->insert(static::TABLE_MAIN, $values);
    }
}