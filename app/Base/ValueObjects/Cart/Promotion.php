<?php

namespace App\Base\ValueObjects\Cart;

use App\DataTypes\Price;

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
