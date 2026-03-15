<?php

namespace App\Store\Exceptions\FieldTypes;

use App\Store\Exceptions\LunarException;

class FieldTypeMissingException extends LunarException
{
    public function __construct(string $classname)
    {
        $this->message = __('store::exceptions.fieldtype_missing', [
            'class' => $classname,
        ]);
    }
}
