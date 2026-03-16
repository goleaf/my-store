<?php

namespace App\Base\DataTransferObjects;

class PaymentCheck
{
    public function __construct(
        public bool $successful,
        public string $label,
        public string $message
    ) {}
}
