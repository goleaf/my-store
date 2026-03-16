<?php

namespace App\Database\Factories;

use App\Models\Cart;
use App\Models\Channel;
use App\Models\Currency;

class CartFactory extends BaseFactory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'customer_id' => null,
            'merged_id' => null,
            'currency_id' => Currency::factory(),
            'channel_id' => Channel::factory(),
            'session_id' => null,
            'zone_id' => null,
            'completed_at' => null,
            'meta' => [],
        ];
    }
}
