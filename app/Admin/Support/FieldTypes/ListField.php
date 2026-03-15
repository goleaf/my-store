<?php

namespace App\Admin\Support\FieldTypes;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\KeyValue;
use App\Admin\Support\Synthesizers\ListSynth;
use App\Store\Models\Attribute;

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
