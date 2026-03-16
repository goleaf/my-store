<?php

namespace App\Support\FieldTypes;

use App\Models\Attribute;
use App\Support\Forms\Components\YouTube as YouTubeInput;
use App\Support\Synthesizers\YouTubeSynth;
use Filament\Forms\Components\Component;

class YouTube extends BaseFieldType
{
    protected static string $synthesizer = YouTubeSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        return YouTubeInput::make($attribute->handle)
            ->live(debounce: 200)
            ->when(filled($attribute->validation_rules), fn (YouTubeInput $component) => $component->rules($attribute->validation_rules))
            ->required((bool) $attribute->required)
            ->helperText(
                $attribute->translate('description') ?? __('admin::components.forms.youtube.helperText')
            );
    }
}
