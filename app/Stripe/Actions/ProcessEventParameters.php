<?php

namespace App\Stripe\Actions;

use App\Stripe\Concerns\ProcessesEventParameters;
use App\Stripe\DataTransferObjects\EventParameters;
use Stripe\Event;

class ProcessEventParameters implements ProcessesEventParameters
{
    public function handle(Event $event): EventParameters
    {
        return new EventParameters(
            paymentIntentId: $event->data->object->id,
            orderId: $event->data->object->metadata?->order_id,
        );
    }
}
