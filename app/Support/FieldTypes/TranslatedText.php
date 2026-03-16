<?php

namespace App\Support\FieldTypes;

use App\Http\Requests\Support\Fields\ConfiguredFieldRequest;
use App\Models\Attribute;
use App\Support\Synthesizers\TranslatedTextSynth;
use Filament\Schemas\Components\Component;
use App\Support\Forms\Components;

class TranslatedText extends BaseFieldType
{
    protected static string $synthesizer = TranslatedTextSynth::class;

    public static function getConfigurationFields(): array
    {
        return TextField::getConfigurationFields();
    }

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $request = (new ConfiguredFieldRequest)
            ->forField($attribute->handle)
            ->required((bool) $attribute->required)
            ->withRules($attribute->validation_rules);

        return Components\TranslatedText::make($attribute->handle)
            ->optionRichtext((bool) $attribute->configuration->get('richtext'))
            ->rules($request->fieldRules($attribute->handle))
            ->required($request->fieldHasRule($attribute->handle, 'required'))
            ->helperText($attribute->translate('description'));
    }
}
