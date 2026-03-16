<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\StorefrontSessionInterface;

/**
 * @method static void forget()
 * @method static void initCustomerGroups()
 * @method static void initChannel()
 * @method static \App\Store\Models\Contracts\Customer|null initCustomer()
 * @method static string getSessionKey()
 * @method static \App\Store\Managers\StorefrontSessionManager setChannel(\App\Store\Models\Contracts\Channel|string $channel)
 * @method static \App\Store\Managers\StorefrontSessionManager setCustomer(\App\Store\Models\Contracts\Customer $customer)
 * @method static \App\Store\Models\Contracts\Customer|null getCustomer()
 * @method static \App\Store\Managers\StorefrontSessionManager setCustomerGroups(\Illuminate\Support\Collection $customerGroups)
 * @method static \App\Store\Managers\StorefrontSessionManager setCustomerGroup(\App\Store\Models\Contracts\CustomerGroup $customerGroup)
 * @method static \App\Store\Managers\StorefrontSessionManager resetCustomerGroups()
 * @method static \App\Store\Models\Contracts\Channel getChannel()
 * @method static \Illuminate\Support\Collection|null getCustomerGroups()
 * @method static \App\Store\Managers\StorefrontSessionManager setCurrency(\App\Store\Models\Contracts\Currency $currency)
 * @method static \App\Store\Models\Contracts\Currency getCurrency()
 *
 * @see \App\Store\Managers\StorefrontSessionManager
 */
class StorefrontSession extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return StorefrontSessionInterface::class;
    }
}
