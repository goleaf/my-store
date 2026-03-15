<?php

namespace App\Admin\Support\Synthesizers;

use App\Store\FieldTypes\Text;

class TextSynth extends AbstractFieldSynth
{
    public static $key = 'lunar_text_field';

    protected static $targetClass = Text::class;
}
