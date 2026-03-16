<?php

namespace App\Store\Actions\Carts;

use App\Store\Actions\AbstractAction;
use App\Store\Exceptions\CartLineIdMismatchException;
use App\Store\Facades\DB;
use App\Store\Models\Cart;
use App\Store\Models\CartLine;
use App\Store\Models\Contracts\Cart as CartContract;

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
        CartContract $cart,
        int $cartLineId
    ): self {
        /** @var Cart $cart */
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
