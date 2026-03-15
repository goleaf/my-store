<?php

namespace App\Admin\Support\FieldTypes;

use Filament\Forms\Components\Component;
use Livewire\Livewire;
use App\Admin\Support\Synthesizers\TextSynth;
use App\Store\Models\Attribute;

abstract class BaseFieldType
{
    protected static string $synthesizer = TextSynth::class;

    public static function getConfigurationFields(): array
    {
        return [];
    }

    abstract public static function getFilamentComponent(Attribute $attribute): Component;

    public static function synthesize()
    {
        Livewire::propertySynthesizer(static::$synthesizer);
    }
}
