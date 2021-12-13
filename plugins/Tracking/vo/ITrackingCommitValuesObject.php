<?php

namespace plugin\tracking\vo;

interface ITrackingCommitValuesObject
{
    public function getHash(): string;
    public function getMessage(): string;
    public function getDate(): string;
    public function getProjectName(): string;
    public function getProjectID(): string;
    public function getWebUrl(): string;
    public function getUrlToProject(): string;
    public function getValues(): array;
    public function setID(int $id): void;
}