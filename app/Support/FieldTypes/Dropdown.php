<?php

namespace App\Support\FieldTypes;

use App\Http\Requests\Support\Fields\ConfiguredFieldRequest;
use App\Models\Attribute;
use App\Support\Synthesizers\DropdownSynth;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;

class Dropdown extends BaseFieldType
{
    protected static string $synthesizer = DropdownSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $request = (new ConfiguredFieldRequest)
            ->forField($attribute->handle)
            ->required((bool) $attribute->required)
            ->withRules($attribute->validation_rules);

        return Select::make($attribute->handle)
            ->options(
                collect($attribute->configuration->get('lookups'))->mapWithKeys(
                    fn ($lookup) => [$lookup['value'] => $lookup['label'] ?? $lookup['value']]
                )
            )
            ->rules($request->fieldRules($attribute->handle))
            ->required($request->fieldHasRule($attribute->handle, 'required'))
            ->helperText($attribute->translate('description'));
    }

    public static function getConfigurationFields(): array
    {
        return [
            KeyValue::make('lookups')->label(
                __('admin::fieldtypes.dropdown.form.lookups.label')
            )
                ->keyLabel(__('admin::fieldtypes.dropdown.form.lookups.key_label'))
                ->valueLabel(__('admin::fieldtypes.dropdown.form.lookups.value_label'))
                ->formatStateUsing(function ($state) {
                    return collect($state)->mapWithKeys(
                        fn ($lookup) => [$lookup['label'] => $lookup['value'] ?? $lookup['label']]
                    )->toArray();
                })
                ->mutateDehydratedStateUsing(function ($state) {
                    return collect($state)->map(function ($value, $label) {
                        return [
                            'label' => $label ?? $value,
                            'value' => $value,
                        ];
                    })->values()->toArray();
                }),
        ];
    }
}
