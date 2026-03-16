<?php

namespace App\Base;

use App\Models\Contracts\Order;

interface OrderReferenceGeneratorInterface
{
    /**
     * Generate a reference for the order.
     */
    public function generate(Order $order): string;
}
