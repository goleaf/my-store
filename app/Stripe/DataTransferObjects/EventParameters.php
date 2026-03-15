<?php

namespace App\Stripe\DataTransferObjects;

class EventParameters
{
    public function __construct(
        public string $paymentIntentId,
        public ?int $orderId = null,
    ) {}
}
