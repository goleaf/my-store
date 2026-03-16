<?php

namespace App\Support\Synthesizers;

use App\Store\FieldTypes\Number;

class NumberSynth extends AbstractFieldSynth
{
    public static $key = 'store_number_field';

    protected static $targetClass = Number::class;
}
