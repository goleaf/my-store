<?php

namespace App\Stripe\Facades;

use Illuminate\Support\Facades\Facade;
use App\Stripe\MockClient;
use Stripe\ApiRequestor;

/**
 * @method static \Stripe\StripeClient getClient()
 * @method static string|null getCartIntentId(\App\Store\Models\Contracts\Cart $cart)
 * @method static \Stripe\PaymentIntent fetchOrCreateIntent(\App\Store\Models\Contracts\Cart $cart, array $createOptions = [])
 * @method static \Stripe\PaymentMethod|null getPaymentMethod(string $paymentMethodId)
 * @method static \Stripe\PaymentIntent createIntent(\App\Store\Models\Contracts\Cart $cart, array $opts = [])
 * @method static void updateShippingAddress(\App\Store\Models\Contracts\Cart $cart)
 * @method static void updateIntent(\App\Store\Models\Contracts\Cart $cart, array $values)
 * @method static void updateIntentById(string $id, array $values)
 * @method static void syncIntent(\App\Store\Models\Contracts\Cart $cart)
 * @method static void cancelIntent(\App\Store\Models\Contracts\Cart $cart, \App\Stripe\Enums\CancellationReason $reason)
 * @method static \Stripe\PaymentIntent|null fetchIntent(string $intentId, void $options = null)
 * @method static \Illuminate\Support\Collection getCharges(string $paymentIntentId)
 * @method static \Stripe\Charge getCharge(string $chargeId)
 *
 * @see \App\Stripe\Managers\StripeManager
 */
class Stripe extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'store:stripe';
    }

    public static function fake(): void
    {
        $mockClient = new MockClient;
        ApiRequestor::setHttpClient($mockClient);
    }
}
