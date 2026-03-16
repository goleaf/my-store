<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\TaxManagerInterface;

/**
 * @method static void createSystemDriver()
 * @method static mixed buildProvider(string $provider)
 * @method static void getDefaultDriver()
 * @method static mixed driver(string|null $driver = null)
 * @method static \App\Managers\TaxManager extend(string $driver, \Closure $callback)
 * @method static array getDrivers()
 * @method static \Illuminate\Contracts\Container\Container getContainer()
 * @method static \App\Managers\TaxManager setContainer(\Illuminate\Contracts\Container\Container $container)
 * @method static \App\Managers\TaxManager forgetDrivers()
 *
 * @see \App\Managers\TaxManager
 */
class Taxes extends Facade
{
    public static function getFacadeAccessor()
    {
        return TaxManagerInterface::class;
    }
}
