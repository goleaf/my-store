<?php

namespace App\Stripe\DataTransferObjects;

use App\Models\Contracts\Order;
use Stripe\PaymentIntent;

class OrderIntent
{
    public function __construct(
        public Order $order,
        public PaymentIntent $paymentIntent
    ) {}
}
