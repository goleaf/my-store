<?php

namespace App\Store\Actions\Carts;

use App\Store\Actions\AbstractAction;
use App\Store\DataTypes\ShippingOption;
use App\Store\Models\Cart;
use App\Store\Models\Contracts\Cart as CartContract;

class SetShippingOption extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        CartContract $cart,
        ShippingOption $shippingOption
    ): self {
        /** @var Cart $cart */
        $cart->shippingAddress->shippingOption = $shippingOption;
        $cart->shippingAddress->update([
            'shipping_option' => $shippingOption->getIdentifier(),
        ]);

        return $this;
    }
}
