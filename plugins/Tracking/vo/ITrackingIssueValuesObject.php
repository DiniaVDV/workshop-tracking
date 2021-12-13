<?php

namespace plugin\tracking\vo;

interface ITrackingIssueValuesObject
{
    public function getStartDate(): string;
    public function getSpentOn(): string;
    public function getProjectName(): string;
    public function getProjectID(): string;
    public function getPriorityName(): string;
    public function getUrl(): string;
    public function getCommets(): string;
    public function getHour(): string;
    public function getSubject(): string;
    public function getValues(): array;
    public function setID(int $id): void;
}