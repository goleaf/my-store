<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\DataTypes\ShippingOption;
use App\Models\Cart;
use App\Models\Contracts\Cart as CartContract;

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
