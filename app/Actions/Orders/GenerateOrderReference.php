<?php

namespace App\Store\Actions\Orders;

use App\Store\Models\Contracts\Order as OrderContract;

class GenerateOrderReference
{
    /**
     * Execute the action.
     *
     * @return string
     */
    public function execute(OrderContract $order)
    {
        $generator = config('store.orders.reference_generator');

        if (! $generator) {
            return null;
        }

        return app($generator)->generate($order);
    }
}
