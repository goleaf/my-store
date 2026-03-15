<?php

namespace App\Store\Exceptions\FieldTypes;

use App\Store\Exceptions\LunarException;

class InvalidFieldTypeException extends LunarException
{
    public function __construct(string $classname)
    {
        $this->message = __('store::exceptions.invalid_fieldtype', [
            'class' => $classname,
        ]);
    }
}
