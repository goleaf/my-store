<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\PricingManagerInterface;

/**
 * @method static \App\Store\Managers\PricingManager for(\App\Store\Base\Purchasable $purchasable)
 * @method static \App\Store\Managers\PricingManager user(\Illuminate\Contracts\Auth\Authenticatable|null $user)
 * @method static \App\Store\Managers\PricingManager guest()
 * @method static \App\Store\Managers\PricingManager currency(\App\Store\Models\Contracts\Currency|null $currency)
 * @method static \App\Store\Managers\PricingManager qty(int $qty)
 * @method static \App\Store\Managers\PricingManager customerGroups(\Illuminate\Support\Collection|null $customerGroups)
 * @method static \App\Store\Managers\PricingManager customerGroup(\App\Store\Models\Contracts\CustomerGroup|null $customerGroup)
 * @method static \App\Store\Base\DataTransferObjects\PricingResponse get()
 *
 * @see \App\Store\Managers\PricingManager
 */
class Pricing extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return PricingManagerInterface::class;
    }
}
