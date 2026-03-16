<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface Price
{
    /**
     * Return the priceable relationship.
     */
    public function priceable(): MorphTo;

    /**
     * Return the currency relationship.
     */
    public function currency(): BelongsTo;

    /**
     * Return the customer group relationship.
     */
    public function customerGroup(): BelongsTo;

    /**
     * Return the price exclusive of tax.
     */
    public function priceExTax(): \App\DataTypes\Price;

    /**
     * Return the price inclusive of tax.
     */
    public function priceIncTax(): int|\App\DataTypes\Price;
}
