<?php

namespace App\Stripe\DataTransferObjects;

use App\Store\Models\Contracts\Order as OrderContract;
use Stripe\PaymentIntent;

class OrderIntent
{
    public function __construct(
        public OrderContract $order,
        public PaymentIntent $paymentIntent
    ) {}
}
