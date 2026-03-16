<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\CartSessionInterface;

/**
 * @method static bool allowsMultipleOrdersPerCart()
 * @method static \App\Models\Cart|null current(bool $estimateShipping = false, bool $calculate = true)
 * @method static \App\Managers\CartSessionManager estimateShippingUsing(array $meta)
 * @method static array getShippingEstimateMeta()
 * @method static void forget(bool $delete = true)
 * @method static \App\Models\Cart|null manager()
 * @method static void associate(\App\Models\Contracts\Cart $cart, \Illuminate\Contracts\Auth\Authenticatable $user, string $policy)
 * @method static \App\Models\Contracts\Cart use(\App\Models\Contracts\Cart $cart)
 * @method static void estimateShipping()
 * @method static string getSessionKey()
 * @method static void setChannel(\App\Models\Contracts\Channel $channel)
 * @method static void setCurrency(\App\Models\Contracts\Currency $currency)
 * @method static \App\Models\Contracts\Currency getCurrency()
 * @method static \App\Models\Contracts\Channel getChannel()
 * @method static \Illuminate\Support\Collection getShippingOptions()
 * @method static \App\Models\Order createOrder(bool $forget = true)
 *
 * @see \App\Managers\CartSessionManager
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
