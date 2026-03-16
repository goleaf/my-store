<?php

namespace App\Shipping\Observers;

use App\Models\Order;
use App\Shipping\DataTransferObjects\PostcodeLookup;
use App\Shipping\Facades\Shipping;
use App\Models\Contracts;

class OrderObserver
{
    public function updated(Contracts\Order $order): void
    {
        $this->updateShippingZone(
            $order
        );
    }

    public function created(Contracts\Order $order): void
    {
        $this->updateShippingZone(
            $order
        );
    }

    protected function updateShippingZone(Contracts\Order $order): void
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
    public function indexing(Contracts\Order $order): void
    {
        /** @var Order $order */
        $order->addSearchableAttribute('shipping_zone', $order->meta?->shipping_zone ?? null);
    }
}
