<?php

namespace App\Observers;

use App\Base\Purchasable;
use App\Exceptions\NonPurchasableItemException;
use App\Models\Contracts\CartLine;

class CartLineObserver
{
    /**
     * Handle the \App\Models\CartLine "creating" event.
     *
     * @return void
     */
    public function creating(CartLine $cartLine)
    {
        /** @var \App\Models\CartLine $cartLine */
        if (! $cartLine->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($cartLine->purchasable_type);
        }
    }

    /**
     * Handle the \App\Models\CartLine "updated" event.
     *
     * @return void
     */
    public function updating(CartLine $cartLine)
    {
        /** @var \App\Models\CartLine $cartLine */
        if (! $cartLine->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($cartLine->purchasable_type);
        }
    }
}
