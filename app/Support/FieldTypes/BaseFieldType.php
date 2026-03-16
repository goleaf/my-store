<?php

namespace App\Support\FieldTypes;

use App\Models\Attribute;
use App\Support\Synthesizers\TextSynth;
use Filament\Schemas\Components\Component;
use Livewire\Livewire;

abstract class BaseFieldType
{
    protected static string $synthesizer = TextSynth::class;

    public static function getConfigurationFields(): array
    {
        return [];
    }

    abstract public static function getFilamentComponent(Attribute $attribute): Component;

    public static function synthesize(): void
    {
        Livewire::propertySynthesizer(static::$synthesizer);
    }
}
