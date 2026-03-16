<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\StorefrontSessionInterface;

/**
 * @method static void forget()
 * @method static void initCustomerGroups()
 * @method static void initChannel()
 * @method static \App\Models\Contracts\Customer|null initCustomer()
 * @method static string getSessionKey()
 * @method static \App\Managers\StorefrontSessionManager setChannel(\App\Models\Contracts\Channel|string $channel)
 * @method static \App\Managers\StorefrontSessionManager setCustomer(\App\Models\Contracts\Customer $customer)
 * @method static \App\Models\Contracts\Customer|null getCustomer()
 * @method static \App\Managers\StorefrontSessionManager setCustomerGroups(\Illuminate\Support\Collection $customerGroups)
 * @method static \App\Managers\StorefrontSessionManager setCustomerGroup(\App\Models\Contracts\CustomerGroup $customerGroup)
 * @method static \App\Managers\StorefrontSessionManager resetCustomerGroups()
 * @method static \App\Models\Contracts\Channel getChannel()
 * @method static \Illuminate\Support\Collection|null getCustomerGroups()
 * @method static \App\Managers\StorefrontSessionManager setCurrency(\App\Models\Contracts\Currency $currency)
 * @method static \App\Models\Contracts\Currency getCurrency()
 *
 * @see \App\Managers\StorefrontSessionManager
 */
class StorefrontSession extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return StorefrontSessionInterface::class;
    }
}
