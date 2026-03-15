<?php

namespace App\Store\Base;

interface TelemetryServiceInterface
{
    public function optOut(): void;

    public function getInsightsUrl(): string;

    public function getCacheKey(): string;

    public function shouldRun(): bool;

    public function run(): void;
}
