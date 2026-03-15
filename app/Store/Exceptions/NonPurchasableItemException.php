<?php

namespace App\Store\Exceptions;

class NonPurchasableItemException extends LunarException
{
    public function __construct(string $classname)
    {
        $this->message = __('store::exceptions.non_purchasable_item', [
            'class' => $classname,
        ]);
    }
}
