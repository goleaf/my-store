<?php

namespace App\Support\Forms;

use App\FieldTypes\Text;
use App\Models\Attribute;
use App\Support\FieldTypes\Dropdown;
use App\Support\FieldTypes\File;
use App\Support\FieldTypes\ListField;
use App\Support\FieldTypes\Number;
use App\Support\FieldTypes\TextField;
use App\Support\FieldTypes\Toggle;
use App\Support\FieldTypes\TranslatedText;
use App\Support\FieldTypes\Vimeo;
use App\Support\FieldTypes\YouTube;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Collection;
use App\Base\FieldType;
use App\FieldTypes;

class AttributeData
{
    protected array $fieldTypes = [
        FieldTypes\Dropdown::class => Dropdown::class,
        FieldTypes\ListField::class => ListField::class,
        Text::class => TextField::class,
        FieldTypes\TranslatedText::class => TranslatedText::class,
        FieldTypes\Toggle::class => Toggle::class,
        FieldTypes\YouTube::class => YouTube::class,
        FieldTypes\Vimeo::class => Vimeo::class,
        FieldTypes\Number::class => Number::class,
        FieldTypes\File::class => File::class,
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
                if ($state instanceof FieldType) {
                    return $state->getValue();
                }

                return $state;
            })
            ->mutateDehydratedStateUsing(function ($state) use ($attribute) {
                if ($attribute->type == FieldTypes\File::class) {
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
