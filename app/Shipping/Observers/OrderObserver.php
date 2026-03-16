<?php

namespace App\Shipping\Observers;

use App\Models\Contracts\Order as OrderContract;
use App\Models\Order;
use App\Shipping\DataTransferObjects\PostcodeLookup;
use App\Shipping\Facades\Shipping;

class OrderObserver
{
    public function updated(OrderContract $order): void
    {
        $this->updateShippingZone(
            $order
        );
    }

    public function created(OrderContract $order): void
    {
        $this->updateShippingZone(
            $order
        );
    }

    protected function updateShippingZone(OrderContract $order): void
    {
        $shippingAddress = $order->shippingAddress ?: $order->cart?->shippingAddress;

        if ($shippingAddress && $shippingAddress->postcode) {
            $postcodeLookup = new PostcodeLookup(
                $shippingAddress->country,
                $shippingAddress->postcode
            );

            $shippingZones = Shipping::zones()->postcode($postcodeLookup)->get();

            if ($shippingZone = $shippingZones->first()) {
                Order::withoutSyncingToSearch(function () use ($order, $shippingZone) {
                    $order->shippingZone()->sync([$shippingZone->id]);
                });
                $meta = (array) $order->meta;
                $meta['shipping_zone'] = $shippingZone->name;
                $order->meta = $meta;
                $order->saveQuietly();
            }
        }
    }

    /**
     * Called when we're about to index the order.
     **/
    public function indexing(OrderContract $order): void
    {
        /** @var Order $order */
        $order->addSearchableAttribute('shipping_zone', $order->meta?->shipping_zone ?? null);
    }
}
