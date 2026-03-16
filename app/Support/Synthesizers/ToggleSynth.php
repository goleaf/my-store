<?php

namespace App\Support\Synthesizers;

use App\Store\FieldTypes\Toggle;

class ToggleSynth extends AbstractFieldSynth
{
    public static $key = 'store_toggle_field';

    protected static $targetClass = Toggle::class;

    public function dehydrate($target)
    {
        return [$target->getValue(), []];
    }

    public function hydrate($value)
    {
        $instance = new static::$targetClass;
        $instance->setValue($value);

        return $instance;
    }

    public function get(&$target, $key)
    {
        return $target->{$key};
    }

    public function set(&$target, $key, $value)
    {
        $target->{$key} = $value;
    }
}
