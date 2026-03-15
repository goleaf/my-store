<?php

namespace App\Store\Database\Factories;

use App\Store\Models\Cart;
use App\Store\Models\Channel;
use App\Store\Models\Currency;

class CartFactory extends BaseFactory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'customer_id' => null,
            'merged_id' => null,
            'currency_id' => Currency::factory(),
            'channel_id' => Channel::factory(),
            'completed_at' => null,
            'meta' => [],
        ];
    }
}
