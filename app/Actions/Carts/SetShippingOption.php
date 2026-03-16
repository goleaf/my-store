<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\DataTypes\ShippingOption;
use App\Models\Contracts\Cart;

class SetShippingOption extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        Cart $cart,
        ShippingOption $shippingOption
    ): self {
        /** @var \App\Models\Cart $cart */
        $cart->shippingAddress->shippingOption = $shippingOption;
        $cart->shippingAddress->update([
            'shipping_option' => $shippingOption->getIdentifier(),
        ]);

        return $this;
    }
}
