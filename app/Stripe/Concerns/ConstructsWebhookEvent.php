<?php

namespace App\Stripe\Concerns;

interface ConstructsWebhookEvent
{
    public function constructEvent(string $jsonPayload, string $signature, string $secret);
}
