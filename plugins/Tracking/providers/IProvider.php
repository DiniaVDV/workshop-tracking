<?php

namespace tracking\providers;

interface IProvider
{
    public function onInit(SettingValuesObject $settings, ITrackingObject $object);
    public function loadRemoteData(): array;
}