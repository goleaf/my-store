<?php

namespace App\Support\Synthesizers;

use App\Store\FieldTypes\Vimeo;

class VimeoSynth extends AbstractFieldSynth
{
    public static $key = 'lunar_vimeo_field';

    protected static $targetClass = Vimeo::class;
}
