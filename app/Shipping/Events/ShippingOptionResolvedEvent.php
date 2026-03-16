<?php

namespace App\Shipping\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\DataTypes\ShippingOption;
use App\Shipping\Models\ShippingRate;
use App\Models\Contracts\Cart;

class ShippingOptionResolvedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The resolved shipping option.
     */
    public ShippingOption $shippingOption;

    /**
     * The instance of the shipping method.
     */
    public ShippingRate $shippingRate;

    /**
     * The instance of the cart.
     */
    public Cart $cart;

    public function __construct(Cart $cart, ShippingRate $shippingRate, ShippingOption $shippingOption)
    {
        $this->cart = $cart;
        $this->shippingRate = $shippingRate;
        $this->shippingOption = $shippingOption;
    }
}
