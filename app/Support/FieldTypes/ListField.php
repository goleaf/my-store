<?php

namespace App\Support\FieldTypes;

use App\Http\Requests\Support\Fields\ConfiguredFieldRequest;
use App\Models\Attribute;
use App\Support\Synthesizers\ListSynth;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Component;

class ListField extends BaseFieldType
{
    protected static string $synthesizer = ListSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $request = (new ConfiguredFieldRequest)
            ->forField($attribute->handle)
            ->required((bool) $attribute->required)
            ->withRules($attribute->validation_rules);

        return KeyValue::make($attribute->handle)
            ->reorderable()
            ->dehydrateStateUsing(function ($state) {
                return $state;
            })
            ->rules($request->fieldRules($attribute->handle))
            ->required($request->fieldHasRule($attribute->handle, 'required'))
            ->helperText($attribute->translate('description'));
    }
}
