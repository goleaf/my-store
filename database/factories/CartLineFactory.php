<?php

namespace App\Database\Factories;

use App\Models\Cart;
use App\Models\CartLine;
use App\Models\ProductVariant;

class CartLineFactory extends BaseFactory
{
    protected $model = CartLine::class;

    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'purchasable_type' => ProductVariant::morphName(),
            'purchasable_id' => ProductVariant::factory(),
            'meta' => null,
        ];
    }
}
