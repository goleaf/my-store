<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\CartSessionInterface;

/**
 * @method static bool allowsMultipleOrdersPerCart()
 * @method static \App\Store\Models\Cart|null current(bool $estimateShipping = false, bool $calculate = true)
 * @method static \App\Store\Managers\CartSessionManager estimateShippingUsing(array $meta)
 * @method static array getShippingEstimateMeta()
 * @method static void forget(bool $delete = true)
 * @method static \App\Store\Models\Cart|null manager()
 * @method static void associate(\App\Store\Models\Contracts\Cart $cart, \Illuminate\Contracts\Auth\Authenticatable $user, string $policy)
 * @method static \App\Store\Models\Contracts\Cart use(\App\Store\Models\Contracts\Cart $cart)
 * @method static void estimateShipping()
 * @method static string getSessionKey()
 * @method static void setChannel(\App\Store\Models\Contracts\Channel $channel)
 * @method static void setCurrency(\App\Store\Models\Contracts\Currency $currency)
 * @method static \App\Store\Models\Contracts\Currency getCurrency()
 * @method static \App\Store\Models\Contracts\Channel getChannel()
 * @method static \Illuminate\Support\Collection getShippingOptions()
 * @method static \App\Store\Models\Order createOrder(bool $forget = true)
 *
 * @see \App\Store\Managers\CartSessionManager
 */
class CartSession extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return CartSessionInterface::class;
    }
}
