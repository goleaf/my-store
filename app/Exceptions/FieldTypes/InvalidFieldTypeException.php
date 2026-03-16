<?php

namespace App\Store\Exceptions\FieldTypes;

use App\Store\Exceptions\StoreException;

class InvalidFieldTypeException extends StoreException
{
    public function __construct(string $classname)
    {
        $this->message = __('store::exceptions.invalid_fieldtype', [
            'class' => $classname,
        ]);
    }
}
