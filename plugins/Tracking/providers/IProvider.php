<?php

interface IProvider
{
    public function create(ValuesObject $valuesObject): int;
    public function loadRemoteData(): array;
}