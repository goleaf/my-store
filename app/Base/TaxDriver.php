<?php

namespace App\Base;

use App\Base\ValueObjects\Cart\TaxBreakdown;
use App\Models\Contracts\CartLine;
use App\Models\Contracts\Currency;

interface TaxDriver
{
    /**
     * Set the shipping address.
     */
    public function setShippingAddress(?Addressable $address = null): self;

    /**
     * Set the currency.
     */
    public function setCurrency(Currency $currency): self;

    /**
     * Set the billing address.
     */
    public function setBillingAddress(?Addressable $address = null): self;

    /**
     * Set the purchasable item.
     */
    public function setPurchasable(Purchasable $purchasable): self;

    /**
     * Set the cart line.
     */
    public function setCartLine(CartLine $cartLine): self;

    /**
     * Return the tax breakdown from a given sub total.
     *
     * @param  int  $subTotal
     */
    public function getBreakdown($subTotal): TaxBreakdown;
}
