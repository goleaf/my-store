<?php

namespace App\Store\Exceptions;

class DisallowMultipleCartOrdersException extends LunarException
{
    public function __construct()
    {
        $this->message = __('store::exceptions.disallow_multiple_cart_orders');
    }
}
