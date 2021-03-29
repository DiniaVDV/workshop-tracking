<?php

interface IProvider
{
    public function create(): int;
    public function loadRemoteData(): array;
}