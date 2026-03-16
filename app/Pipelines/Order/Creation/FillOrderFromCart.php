<?php

namespace App\Pipelines\Order\Creation;

use Closure;
use Illuminate\Support\Facades\App;
use App\Actions\Orders\GenerateOrderReference;
use App\Models\Contracts\Currency;
use App\Models\Contracts\Order;

class FillOrderFromCart
{
    /**
     * @param  Closure(\App\Models\Contracts\Order): mixed  $next
     */
    public function handle(Order $order, Closure $next): mixed
    {
        /** @var \App\Models\Order $order */
        $cart = $order->cart->calculate();

        $order->fill([
            'customer_id' => $cart->customer_id,
            'channel_id' => $cart->channel_id,
            'status' => config('store.orders.draft_status'),
            'reference' => null,
            'customer_reference' => null,
            'sub_total' => $cart->subTotal->value,
            'total' => $cart->total->value,
            'discount_total' => $cart->discountTotal?->value,
            'discount_breakdown' => [],
            'shipping_total' => $cart->shippingTotal?->value ?: 0,
            'shipping_breakdown' => $cart->shippingBreakdown,
            'tax_breakdown' => $cart->taxBreakdown,
            'tax_total' => $cart->taxTotal->value,
            'currency_code' => $cart->currency->code,
            'exchange_rate' => $cart->currency->exchange_rate,
            'compare_currency_code' => App::make(Currency::class)::getDefault()?->code,
            'meta' => $cart->meta,
        ])->save();

        $order->update([
            'reference' => app(GenerateOrderReference::class)->execute($order),
        ]);

        return $next($order);
    }
}
