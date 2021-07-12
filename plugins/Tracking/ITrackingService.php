<?php

namespace plugin\tracking;

interface ITrackingService
{
    public function loadUserID(): bool;
}