<?php

interface ITrackingCommitValuesObject
{
    public function getHash(): string;
    public function getMessage(): string;
    public function getDate(): string;
    public function getProjectName(): string;
    public function getProjectID(): string;
    public function getWebUrl(): string;
    public function getUrlToProject(): string;
}