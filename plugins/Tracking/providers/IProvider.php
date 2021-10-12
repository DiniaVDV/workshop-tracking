<?php

namespace plugin\tracking\providers;

interface IProvider
{
    public function loadRemoteUserID(): ?int;
}