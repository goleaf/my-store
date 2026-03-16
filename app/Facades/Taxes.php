<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\TaxManagerInterface;

/**
 * @method static void createSystemDriver()
 * @method static mixed buildProvider(string $provider)
 * @method static void getDefaultDriver()
 * @method static mixed driver(string|null $driver = null)
 * @method static \App\Store\Managers\TaxManager extend(string $driver, \Closure $callback)
 * @method static array getDrivers()
 * @method static \Illuminate\Contracts\Container\Container getContainer()
 * @method static \App\Store\Managers\TaxManager setContainer(\Illuminate\Contracts\Container\Container $container)
 * @method static \App\Store\Managers\TaxManager forgetDrivers()
 *
 * @see \App\Store\Managers\TaxManager
 */
class Taxes extends Facade
{
    public static function getFacadeAccessor()
    {
        return TaxManagerInterface::class;
    }
}
