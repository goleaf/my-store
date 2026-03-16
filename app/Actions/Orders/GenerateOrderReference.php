<?php

namespace App\Actions\Orders;

use App\Models\Contracts\Order;

class GenerateOrderReference
{
    /**
     * Execute the action.
     *
     * @return string
     */
    public function execute(Order $order)
    {
        $generator = config('store.orders.reference_generator');

        if (! $generator) {
            return null;
        }

        return app($generator)->generate($order);
    }
}
