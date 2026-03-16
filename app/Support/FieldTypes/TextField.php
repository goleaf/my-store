<?php

namespace App\Support\FieldTypes;

use App\Http\Requests\Support\Fields\ConfiguredFieldRequest;
use App\Models\Attribute;
use App\Support\Synthesizers\TextSynth;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\Toggle;

class TextField extends BaseFieldType
{
    protected static string $synthesizer = TextSynth::class;

    public static function getConfigurationFields(): array
    {
        return [
            Toggle::make('richtext')->label(
                __('admin::fieldtypes.text.form.richtext.label')
            ),
        ];
    }

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $request = (new ConfiguredFieldRequest)
            ->forField($attribute->handle)
            ->required((bool) $attribute->required)
            ->withRules($attribute->validation_rules);

        if ($attribute->configuration->get('richtext')) {
            return RichEditor::make($attribute->handle)
                ->rules($request->fieldRules($attribute->handle))
                ->required($request->fieldHasRule($attribute->handle, 'required'))
                ->helperText($attribute->translate('description'));
        }

        return TextInput::make($attribute->handle)
            ->rules($request->fieldRules($attribute->handle))
            ->required($request->fieldHasRule($attribute->handle, 'required'))
            ->helperText($attribute->translate('description'));
    }
}
