<?php

namespace App\Support\FieldTypes;

use App\Http\Requests\Support\BooleanFieldRequest;
use App\Models\Attribute;
use App\Support\Synthesizers\ToggleSynth;
use Filament\Forms;
use Filament\Schemas\Components\Component;

class Toggle extends BaseFieldType
{
    protected static string $synthesizer = ToggleSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $request = (new BooleanFieldRequest)
            ->forField($attribute->handle)
            ->required((bool) $attribute->required)
            ->withRules($attribute->validation_rules);

        return Forms\Components\Toggle::make($attribute->handle)
            ->helperText(
                $attribute->translate('description')
            )
            ->default(false)
            ->rules($request->fieldRules($attribute->handle))
            ->required($request->fieldHasRule($attribute->handle, 'required'));
    }
}
