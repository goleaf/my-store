<?php

namespace App\Store\Actions\Carts;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\App;
use App\Store\Actions\AbstractAction;
use App\Store\Exceptions\DisallowMultipleCartOrdersException;
use App\Store\Facades\DB;
use App\Store\Jobs\Orders\MarkAsNewCustomer;
use App\Store\Models\Cart;
use App\Store\Models\Contracts\Cart as CartContract;
use App\Store\Models\Contracts\Order as OrderContract;
use App\Store\Models\Order;

final class CreateOrder extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        CartContract $cart,
        bool $allowMultipleOrders = false,
        ?int $orderIdToUpdate = null
    ): self {
        $this->passThrough = DB::transaction(function () use ($cart, $allowMultipleOrders, $orderIdToUpdate) {
            /** @var Order $order */
            /** @var Cart $cart */
            $order = $cart->draftOrder($orderIdToUpdate)->first() ?: App::make(OrderContract::class);

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
