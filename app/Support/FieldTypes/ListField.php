<?php

namespace App\Support\FieldTypes;

use App\Store\Models\Attribute;
use App\Support\Synthesizers\ListSynth;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\KeyValue;

class ListField extends BaseFieldType
{
    protected static string $synthesizer = ListSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        return KeyValue::make($attribute->handle)
            ->reorderable()
            ->dehydrateStateUsing(function ($state) {
                return $state;
            })
            ->when(filled($attribute->validation_rules), fn (KeyValue $component) => $component->rules($attribute->validation_rules))
            ->required((bool) $attribute->required)
            ->helperText($attribute->translate('description'));
    }
}
