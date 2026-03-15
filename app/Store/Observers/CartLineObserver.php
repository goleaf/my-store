<?php

namespace App\Store\Observers;

use App\Store\Base\Purchasable;
use App\Store\Exceptions\NonPurchasableItemException;
use App\Store\Models\CartLine;
use App\Store\Models\Contracts\CartLine as CartLineContract;

class CartLineObserver
{
    /**
     * Handle the CartLine "creating" event.
     *
     * @return void
     */
    public function creating(CartLineContract $cartLine)
    {
        /** @var CartLine $cartLine */
        if (! $cartLine->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($cartLine->purchasable_type);
        }
    }

    /**
     * Handle the CartLine "updated" event.
     *
     * @return void
     */
    public function updating(CartLineContract $cartLine)
    {
        /** @var CartLine $cartLine */
        if (! $cartLine->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($cartLine->purchasable_type);
        }
    }
}
