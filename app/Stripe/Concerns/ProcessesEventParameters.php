<?php

namespace App\Stripe\Concerns;

use App\Stripe\DataTransferObjects\EventParameters;
use Stripe\Event;

interface ProcessesEventParameters
{
    public function handle(Event $event): EventParameters;
}
