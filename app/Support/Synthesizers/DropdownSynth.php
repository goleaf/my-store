<?php

namespace App\Support\Synthesizers;

use App\FieldTypes\Dropdown;

class DropdownSynth extends AbstractFieldSynth
{
    public static $key = 'store_dropdown_field';

    protected static $targetClass = Dropdown::class;
}
