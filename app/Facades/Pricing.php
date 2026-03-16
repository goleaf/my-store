<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\PricingManagerInterface;

/**
 * @method static \App\Managers\PricingManager for(\App\Base\Purchasable $purchasable)
 * @method static \App\Managers\PricingManager user(\Illuminate\Contracts\Auth\Authenticatable|null $user)
 * @method static \App\Managers\PricingManager guest()
 * @method static \App\Managers\PricingManager currency(\App\Models\Contracts\Currency|null $currency)
 * @method static \App\Managers\PricingManager qty(int $qty)
 * @method static \App\Managers\PricingManager customerGroups(\Illuminate\Support\Collection|null $customerGroups)
 * @method static \App\Managers\PricingManager customerGroup(\App\Models\Contracts\CustomerGroup|null $customerGroup)
 * @method static \App\Base\DataTransferObjects\PricingResponse get()
 *
 * @see \App\Managers\PricingManager
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
