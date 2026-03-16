<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\TelemetryServiceInterface;

/**
 * @method static void optOut()
 * @method static string getInsightsUrl()
 * @method static string getCacheKey()
 * @method static bool shouldRun()
 * @method static void run()
 *
 * @see \App\Base\TelemetryService
 */
class Telemetry extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return TelemetryServiceInterface::class;
    }
}
