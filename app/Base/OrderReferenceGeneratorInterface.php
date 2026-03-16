<?php

namespace App\Store\Base;

use App\Store\Models\Contracts\Order;

interface OrderReferenceGeneratorInterface
{
    /**
     * Generate a reference for the order.
     */
    public function generate(Order $order): string;
}
