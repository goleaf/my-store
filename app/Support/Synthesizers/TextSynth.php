<?php

namespace App\Support\Synthesizers;

use App\FieldTypes\Text;

class TextSynth extends AbstractFieldSynth
{
    public static $key = 'store_text_field';

    protected static $targetClass = Text::class;
}
