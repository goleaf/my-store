<?php

namespace App\Shipping\Facades;

use Illuminate\Support\Facades\Facade;
use App\Shipping\Interfaces\ShippingMethodManagerInterface;

/**
 * @method static void createFreeShippingDriver()
 * @method static void createFlatRateDriver()
 * @method static void createShipByDriver()
 * @method static void createCollectionDriver()
 * @method static \Illuminate\Support\Collection getSupportedDrivers()
 * @method static \App\Shipping\Resolvers\ShippingZoneResolver zones()
 * @method static \App\Shipping\Resolvers\ShippingRateResolver shippingRates(\App\Models\Contracts\Cart|null $cart = null)
 * @method static \App\Shipping\Resolvers\ShippingOptionResolver shippingOptions(\App\Models\Contracts\Cart|null $cart = null)
 * @method static mixed buildProvider(string $provider)
 * @method static void getDefaultDriver()
 * @method static mixed driver(string|null $driver = null)
 * @method static \App\Shipping\Managers\ShippingManager extend(string $driver, \Closure $callback)
 * @method static array getDrivers()
 * @method static \Illuminate\Contracts\Container\Container getContainer()
 * @method static \App\Shipping\Managers\ShippingManager setContainer(\Illuminate\Contracts\Container\Container $container)
 * @method static \App\Shipping\Managers\ShippingManager forgetDrivers()
 *
 * @see \App\Shipping\Managers\ShippingManager
 */
class Shipping extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return ShippingMethodManagerInterface::class;
    }
}
