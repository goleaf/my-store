<?php

namespace App\Admin\Support\Forms;

use Filament\Forms\Components\Component;
use Illuminate\Support\Collection;
use App\Admin\Support\FieldTypes\Dropdown;
use App\Admin\Support\FieldTypes\File;
use App\Admin\Support\FieldTypes\ListField;
use App\Admin\Support\FieldTypes\Number;
use App\Admin\Support\FieldTypes\TextField;
use App\Admin\Support\FieldTypes\Toggle;
use App\Admin\Support\FieldTypes\TranslatedText;
use App\Admin\Support\FieldTypes\Vimeo;
use App\Admin\Support\FieldTypes\YouTube;
use App\Store\FieldTypes\Dropdown as DrodownFieldType;
use App\Store\FieldTypes\File as FileFieldType;
use App\Store\FieldTypes\ListField as ListFieldFieldType;
use App\Store\FieldTypes\Number as NumberFieldType;
use App\Store\FieldTypes\Text as TextFieldType;
use App\Store\FieldTypes\Toggle as ToggleFieldType;
use App\Store\FieldTypes\TranslatedText as TranslatedTextFieldType;
use App\Store\FieldTypes\Vimeo as VimeoFieldType;
use App\Store\FieldTypes\YouTube as YouTubeFieldType;
use App\Store\Models\Attribute;

class AttributeData
{
    protected array $fieldTypes = [
        DrodownFieldType::class => Dropdown::class,
        ListFieldFieldType::class => ListField::class,
        TextFieldType::class => TextField::class,
        TranslatedTextFieldType::class => TranslatedText::class,
        ToggleFieldType::class => Toggle::class,
        YouTubeFieldType::class => YouTube::class,
        VimeoFieldType::class => Vimeo::class,
        NumberFieldType::class => Number::class,
        FileFieldType::class => File::class,
    ];

    public function getFilamentComponent(Attribute $attribute): Component
    {
        $fieldType = $this->fieldTypes[
        $attribute->type
        ] ?? TextField::class;

        /** @var Component $component */
        $component = $fieldType::getFilamentComponent($attribute);

        return $component
            ->label(
                $attribute->translate('name')
            )
            ->formatStateUsing(function ($state) use ($attribute) {
                if (
                    ! $state ||
                    (get_class($state) != $attribute->type)
                ) {
                    return new $attribute->type;
                }

                return $state;
            })
            ->mutateStateForValidationUsing(function ($state) {
                if ($state instanceof \Lunar\Base\FieldType) {
                    return $state->getValue();
                }

                return $state;
            })
            ->mutateDehydratedStateUsing(function ($state) use ($attribute) {
                if ($attribute->type == FileFieldType::class) {
                    $instance = new $attribute->type;
                    $instance->setValue($state);

                    return $instance;
                }

                if (
                    ! $state ||
                    (get_class($state) != $attribute->type)
                ) {
                    return new $attribute->type;
                }

                return $state;
            })
            ->required($attribute->required)
            ->default($attribute->default_value);
    }

    public function registerFieldType(string $coreFieldType, string $panelFieldType): static
    {
        $this->fieldTypes[$coreFieldType] = $panelFieldType;

        return $this;
    }

    public function getFieldTypes(): Collection
    {
        return collect($this->fieldTypes)->keys();
    }

    public function getConfigurationFields(?string $type = null): array
    {
        $fieldType = $this->fieldTypes[$type] ?? null;

        return $fieldType ? $fieldType::getConfigurationFields() : [];
    }

    public function synthesizeLivewireProperties(): void
    {
        foreach ($this->fieldTypes as $fieldType) {
            $fieldType::synthesize();
        }
    }
}
