<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\TelemetryServiceInterface;

/**
 * @method static void optOut()
 * @method static string getInsightsUrl()
 * @method static string getCacheKey()
 * @method static bool shouldRun()
 * @method static void run()
 *
 * @see \App\Store\Base\TelemetryService
 */
class Telemetry extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return TelemetryServiceInterface::class;
    }
}
