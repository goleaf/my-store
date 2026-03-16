<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\Base\Purchasable;
use App\Exceptions\InvalidCartLineQuantityException;
use App\Models\Contracts\Cart;

class AddOrUpdatePurchasable extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        Cart $cart,
        Purchasable $purchasable,
        int $quantity = 1,
        array $meta = []
    ): self {
        throw_if(! $quantity, InvalidCartLineQuantityException::class);

        /** @var \App\Models\Cart $cart */
        $existing = app(
            config('store.cart.actions.get_existing_cart_line', GetExistingCartLine::class)
        )->execute(
            cart: $cart,
            purchasable: $purchasable,
            meta: $meta
        );

        if ($existing) {
            $existing->update([
                'quantity' => $existing->quantity + $quantity,
            ]);

            return $this;
        }

        $cart->lines()->create([
            'purchasable_id' => $purchasable->id,
            'purchasable_type' => $purchasable->getMorphClass(),
            'quantity' => $quantity,
            'meta' => $meta,
        ]);

        return $this;
    }
}
