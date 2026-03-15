<?php

namespace App\Store\Base;

use App\Store\Models\Contracts\Order as OrderContract;

class OrderReferenceGenerator implements OrderReferenceGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function generate(OrderContract $order): string
    {
        $config = config('store.orders.reference_format', []);

        $reference = str_pad(
            $order->id,
            $config['length'] ?? 8,
            $config['padding_character'] ?? 0,
            $config['padding_direction'] ?? STR_PAD_LEFT
        );

        $prefix = $config['prefix'] ?? '';

        return "{$prefix}{$reference}";
    }
}
