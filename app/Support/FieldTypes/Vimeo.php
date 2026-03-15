<?php

namespace App\Support\FieldTypes;

use App\Store\Models\Attribute;
use App\Support\Forms\Components\Vimeo as VimeoInput;
use App\Support\Synthesizers\VimeoSynth;
use Filament\Forms\Components\Component;

class Vimeo extends BaseFieldType
{
    protected static string $synthesizer = VimeoSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        return VimeoInput::make($attribute->handle)
            ->live(debounce: 200)
            ->when(filled($attribute->validation_rules), fn (VimeoInput $component) => $component->rules($attribute->validation_rules))
            ->required((bool) $attribute->required)
            ->helperText(
                $attribute->translate('description') ?? __('admin::components.forms.youtube.helperText')
            );
    }
}
