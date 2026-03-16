<?php

namespace App\Pipelines\Order\Creation;

use Closure;
use Illuminate\Support\Facades\App;
use App\Models\Contracts\Order;
use App\Models\Contracts\OrderAddress;

class CreateOrderAddresses
{
    /**
     * @param  Closure(\App\Models\Contracts\Order): mixed  $next
     */
    public function handle(Order $order, Closure $next): mixed
    {
        /** @var \App\Models\Order $order */
        $orderAddresses = $order->addresses;

        foreach ($order->cart->addresses as $address) {
            /** @var \App\Models\OrderAddress $addressModel */
            $addressModel = $orderAddresses->first(function ($orderAddress) use ($address) {
                return $orderAddress->type == $address->type &&
                    $orderAddress->postcode == $address->postcode;
            }) ?: App::make(OrderAddress::class);

            $addressModel->fill(
                collect(
                    $address->toArray()
                )->except(['cart_id', 'id'])->merge([
                    'order_id' => $order->id,
                ])->toArray()
            )->save();
        }

        return $next($order->refresh());
    }
}
