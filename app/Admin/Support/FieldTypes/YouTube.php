<?php

namespace App\Admin\Support\FieldTypes;

use Filament\Forms\Components\Component;
use App\Admin\Support\Forms\Components\YouTube as YouTubeInput;
use App\Admin\Support\Synthesizers\YouTubeSynth;
use App\Store\Models\Attribute;

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
