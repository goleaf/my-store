<?php

namespace App\Admin\Support\Synthesizers;

use App\Store\FieldTypes\Number;

class NumberSynth extends AbstractFieldSynth
{
    public static $key = 'lunar_number_field';

    protected static $targetClass = Number::class;
}
