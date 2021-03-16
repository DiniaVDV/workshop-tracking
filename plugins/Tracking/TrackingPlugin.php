<?php

class TrackingPlugin extends ObjectPlugin
{
    public function onCronSyncUsersData(): bool
    {
        $users = $this->plugin->users->search();
        
        return true;
    }
}