<?php

namespace App\Support\Synthesizers;

use App\FieldTypes\Vimeo;

class VimeoSynth extends AbstractFieldSynth
{
    public static $key = 'store_vimeo_field';

    protected static $targetClass = Vimeo::class;
}
