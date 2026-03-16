<?php

namespace App\Exceptions\FieldTypes;

use App\Exceptions\StoreException;

class FieldTypeMissingException extends StoreException
{
    public function __construct(string $classname)
    {
        $this->message = __('store::exceptions.fieldtype_missing', [
            'class' => $classname,
        ]);
    }
}
