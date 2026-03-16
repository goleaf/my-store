<?php

namespace App\Support\FieldTypes;

use App\Http\Requests\Support\Fields\ConfiguredFieldRequest;
use App\Models\Attribute;
use App\Support\Synthesizers\VimeoSynth;
use Filament\Schemas\Components\Component;
use App\Support\Forms\Components;

class Vimeo extends BaseFieldType
{
    protected static string $synthesizer = VimeoSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $request = (new ConfiguredFieldRequest)
            ->forField($attribute->handle)
            ->required((bool) $attribute->required)
            ->withRules($attribute->validation_rules);

        return Components\Vimeo::make($attribute->handle)
            ->live(debounce: 200)
            ->rules($request->fieldRules($attribute->handle))
            ->required($request->fieldHasRule($attribute->handle, 'required'))
            ->helperText(
                $attribute->translate('description') ?? __('admin::components.forms.youtube.helperText')
            );
    }
}
