<?php

class UsersObject extends DataAccessObject
{
    public const TABLE_MAIN = 'users';
    
    public function search(array $search)
    {
        $sql = "SELECT * FROM ".static::TABLE_MAIN;
        
        return $this->select($sql, $search);
    }
}