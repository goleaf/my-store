<?php

namespace App\Support\FieldTypes;

use App\Store\Models\Attribute;
use App\Support\Synthesizers\ToggleSynth;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Toggle as ToggleInput;

class Toggle extends BaseFieldType
{
    protected static string $synthesizer = ToggleSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        return ToggleInput::make($attribute->handle)
            ->helperText(
                $attribute->translate('description')
            )
            ->default(false)
            ->when(filled($attribute->validation_rules), fn (Toggle $component) => $component->rules($attribute->validation_rules))
            ->rule('boolean')
            ->required((bool) $attribute->required);
    }
}
