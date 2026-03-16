<?php

namespace App\Support\Synthesizers;

use App\FieldTypes\YouTube;

class YouTubeSynth extends AbstractFieldSynth
{
    public static $key = 'store_youtube_field';

    protected static $targetClass = YouTube::class;
}
