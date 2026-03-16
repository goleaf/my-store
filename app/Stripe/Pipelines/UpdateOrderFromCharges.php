<?php

namespace App\Stripe\Pipelines;

use App\Stripe\DataTransferObjects\OrderIntent;
use Closure;

class UpdateOrderFromCharges
{
    public function handle(OrderIntent $orderIntent, Closure $next) {}
}
