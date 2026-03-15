<?php

namespace App\Store\Base\ValueObjects\Cart;

use App\Store\DataTypes\Price;

class Promotion
{
    /**
     * Description of the promotion.
     */
    public string $description = '';

    /**
     * Promotion reference.
     */
    public string $reference = '';

    /**
     * Discount amount
     */
    public Price $amount;
}
