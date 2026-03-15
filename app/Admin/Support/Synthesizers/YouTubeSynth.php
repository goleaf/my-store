<?php

namespace App\Admin\Support\Synthesizers;

use App\Store\FieldTypes\YouTube;

class YouTubeSynth extends AbstractFieldSynth
{
    public static $key = 'lunar_youtube_field';

    protected static $targetClass = YouTube::class;
}
