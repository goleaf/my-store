<?php

namespace App\Exceptions\FieldTypes;

use App\Exceptions\StoreException;

class InvalidFieldTypeException extends StoreException
{
    public function __construct(string $classname)
    {
        $this->message = __('store::exceptions.invalid_fieldtype', [
            'class' => $classname,
        ]);
    }
}
