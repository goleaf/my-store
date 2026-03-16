<?php

namespace App\Exceptions;

class NonPurchasableItemException extends StoreException
{
    public function __construct(string $classname)
    {
        $this->message = __('store::exceptions.non_purchasable_item', [
            'class' => $classname,
        ]);
    }
}
