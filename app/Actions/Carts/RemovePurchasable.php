<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\Exceptions\CartLineIdMismatchException;
use App\Facades\DB;
use App\Models\CartLine;
use App\Models\Contracts\Cart;

class RemovePurchasable extends AbstractAction
{
    /**
     * Execute the action
     *
     * @return bool
     *
     * @throws CartLineIdMismatchException
     */
    public function execute(
        Cart $cart,
        int $cartLineId
    ): self {
        /** @var \App\Models\Cart $cart */
        DB::transaction(function () use ($cart, $cartLineId) {
            /** @var CartLine $line */
            $line = $cart->lines()->whereId($cartLineId)->first();

            if (! $line) {
                // If we're trying to remove a line that does not
                // belong to this cart, throw an exception.
                throw new CartLineIdMismatchException(
                    __('store::exceptions.cart_line_id_mismatch')
                );
            }

            $line->delete();
        });

        return $this;
    }
}
