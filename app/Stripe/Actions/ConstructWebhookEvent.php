<?php

namespace App\Stripe\Actions;

use App\Stripe\Concerns\ConstructsWebhookEvent;
use Stripe\Webhook;

class ConstructWebhookEvent implements ConstructsWebhookEvent
{
    public function constructEvent(string $jsonPayload, string $signature, string $secret)
    {
        return Webhook::constructEvent(
            $jsonPayload, $signature, $secret
        );
    }
}
