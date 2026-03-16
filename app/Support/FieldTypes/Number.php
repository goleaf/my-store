<?php

namespace App\Support\FieldTypes;

use App\Http\Requests\Support\Fields\NumericFieldRequest;
use App\Models\Attribute;
use App\Support\Synthesizers\NumberSynth;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components;
use Filament\Schemas\Components\Component;

class Number extends BaseFieldType
{
    protected static string $synthesizer = NumberSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $min = $attribute->configuration->get('min');
        $max = $attribute->configuration->get('max');
        $request = (new NumericFieldRequest)
            ->forField($attribute->handle)
            ->required((bool) $attribute->required)
            ->withRules($attribute->validation_rules)
            ->min(filled($min) ? (float) $min : null)
            ->max(filled($max) ? (float) $max : null);

        $input = TextInput::make($attribute->handle)
            ->numeric()
            ->rules($request->fieldRules($attribute->handle))
            ->required($request->fieldHasRule($attribute->handle, 'required'))
            ->helperText($attribute->translate('description'));

        if (filled($min)) {
            $input->minValue($min);
        }

        if (filled($max)) {
            $input->maxValue($max);
        }

        return $input;
    }

    public static function getConfigurationFields(): array
    {
        return [
            Components\Grid::make(2)->schema([
                TextInput::make('min')
                    ->label(
                        __('admin::fieldtypes.number.form.min.label')
                    )->nullable()->numeric(),
                TextInput::make('max')->label(
                    __('admin::fieldtypes.number.form.max.label')
                )->nullable()->numeric(),
            ]),
        ];
    }
}
