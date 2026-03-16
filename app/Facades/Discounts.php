<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\DiscountManagerInterface;

/**
 * @method static \App\Managers\DiscountManager channel(\App\Models\Contracts\Channel|\Traversable|array $channel)
 * @method static \App\Managers\DiscountManager customerGroup(\App\Models\Contracts\CustomerGroup|\Traversable|array $customerGroups)
 * @method static \Illuminate\Support\Collection getChannels()
 * @method static \Illuminate\Support\Collection getDiscounts(\App\Models\Cart|null $cart = null)
 * @method static \Illuminate\Support\Collection getCustomerGroups()
 * @method static \App\Managers\DiscountManager addType(string $classname)
 * @method static \Illuminate\Support\Collection getTypes()
 * @method static \App\Managers\DiscountManager addApplied(\App\Base\DataTransferObjects\CartDiscount $cartDiscount)
 * @method static \Illuminate\Support\Collection getApplied()
 * @method static \App\Models\Contracts\Cart apply(\App\Models\Contracts\Cart $cart)
 * @method static \App\Managers\DiscountManager resetDiscounts()
 * @method static bool validateCoupon(string $coupon)
 *
 * @see \App\Managers\DiscountManager
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
