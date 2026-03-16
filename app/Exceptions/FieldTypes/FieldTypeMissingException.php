<?php

namespace App\Store\Exceptions\FieldTypes;

use App\Store\Exceptions\StoreException;

class FieldTypeMissingException extends StoreException
{
    public function __construct(string $classname)
    {
        $this->message = __('store::exceptions.fieldtype_missing', [
            'class' => $classname,
        ]);
    }
}
