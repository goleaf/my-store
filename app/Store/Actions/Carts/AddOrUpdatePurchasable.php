<?php

namespace App\Store\Actions\Carts;

use App\Store\Actions\AbstractAction;
use App\Store\Base\Purchasable;
use App\Store\Exceptions\InvalidCartLineQuantityException;
use App\Store\Models\Cart;
use App\Store\Models\Contracts\Cart as CartContract;

class AddOrUpdatePurchasable extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        CartContract $cart,
        Purchasable $purchasable,
        int $quantity = 1,
        array $meta = []
    ): self {
        throw_if(! $quantity, InvalidCartLineQuantityException::class);

        /** @var Cart $cart */
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
