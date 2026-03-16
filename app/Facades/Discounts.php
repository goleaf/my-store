<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\DiscountManagerInterface;

/**
 * @method static \App\Store\Managers\DiscountManager channel(\App\Store\Models\Contracts\Channel|\Traversable|array $channel)
 * @method static \App\Store\Managers\DiscountManager customerGroup(\App\Store\Models\Contracts\CustomerGroup|\Traversable|array $customerGroups)
 * @method static \Illuminate\Support\Collection getChannels()
 * @method static \Illuminate\Support\Collection getDiscounts(\App\Store\Models\Cart|null $cart = null)
 * @method static \Illuminate\Support\Collection getCustomerGroups()
 * @method static \App\Store\Managers\DiscountManager addType(string $classname)
 * @method static \Illuminate\Support\Collection getTypes()
 * @method static \App\Store\Managers\DiscountManager addApplied(\App\Store\Base\DataTransferObjects\CartDiscount $cartDiscount)
 * @method static \Illuminate\Support\Collection getApplied()
 * @method static \App\Store\Models\Contracts\Cart apply(\App\Store\Models\Contracts\Cart $cart)
 * @method static \App\Store\Managers\DiscountManager resetDiscounts()
 * @method static bool validateCoupon(string $coupon)
 *
 * @see \App\Store\Managers\DiscountManager
 */
class Discounts extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return DiscountManagerInterface::class;
    }
}
