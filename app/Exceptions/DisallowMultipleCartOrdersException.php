<?php

namespace App\Exceptions;

class DisallowMultipleCartOrdersException extends StoreException
{
    public function __construct()
    {
        $this->message = __('store::exceptions.disallow_multiple_cart_orders');
    }
}
