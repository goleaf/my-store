<?php

namespace App\Actions\Carts;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\App;
use App\Actions\AbstractAction;
use App\Exceptions\DisallowMultipleCartOrdersException;
use App\Facades\DB;
use App\Jobs\Orders\MarkAsNewCustomer;
use App\Models\Contracts\Cart;
use App\Models\Contracts\Order;

final class CreateOrder extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        Cart $cart,
        bool $allowMultipleOrders = false,
        ?int $orderIdToUpdate = null
    ): self {
        $this->passThrough = DB::transaction(function () use ($cart, $allowMultipleOrders, $orderIdToUpdate) {
            /** @var \App\Models\Order $order */
            /** @var \App\Models\Cart $cart */
            $order = $cart->draftOrder($orderIdToUpdate)->first() ?: App::make(Order::class);

            if ($cart->hasCompletedOrders() && ! $allowMultipleOrders) {
                throw new DisallowMultipleCartOrdersException;
            }

            $order->fill([
                'cart_id' => $cart->id,
                'fingerprint' => $cart->fingerprint(),
            ]);

            $order = app(Pipeline::class)
                ->send($order)
                ->through(
                    config('store.orders.pipelines.creation', [])
                )->thenReturn(function ($order) {
                    return $order;
                });

            $cart->discounts?->each(function ($discount) use ($cart) {
                $discount->markAsUsed($cart)->discount->save();
            });

            $cart->save();

            MarkAsNewCustomer::dispatch($order->id);

            $order->refresh();

            return $order;
        });

        return $this;
    }
}
