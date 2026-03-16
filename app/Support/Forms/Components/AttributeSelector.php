<?php

namespace App\Support\Forms\Components;

use App\Facades\ModelManifest;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Product;
use App\Models\ProductType;
use Filament\Forms\Components\CheckboxList;
use Closure;

class AttributeSelector extends CheckboxList
{
    protected string $view = 'admin::forms.components.attribute-selector';

    protected ?string $attributableType = null;

    public function withType($type)
    {
        $this->attributableType = $type;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadStateFromRelationships();
    }

    public function relationship(string|Closure|null $name = null, string|Closure|null $titleAttribute = null, ?Closure $modifyQueryUsing = null): static
    {
        parent::relationship($name, $titleAttribute, $modifyQueryUsing);

        $type = $this->attributableType;

        $this->saveRelationshipsUsing(static function (CheckboxList $component, ?array $state) use ($type) {
            // Get all current mapped attributes
            $existing = $component->getRelationship()->get();

            $actualClass = ModelManifest::guessModelClass($type);
            // Filter out any that match this attribute type but are not in the saved state.
            $attributes = $existing->reject(
                fn ($attribute) => ! in_array($attribute->id, $state ?? []) && $attribute->attribute_type == $type
            )->pluck('id')->unique()->merge($state)->toArray();

            $component->getRelationship()->sync($attributes);
        });

        return $this;
    }

    public function getAttributeGroups()
    {
        $type = get_class(
            $this->getRelationship()->getParent()
        );

        if ($type === ProductType::morphName()) {
            $type = Product::morphName();
        }

        if ($this->attributableType) {
            $type = $this->attributableType;
        }

        return AttributeGroup::whereAttributableType($type)->orderBy('position')->get();
    }

    public function getSelectedAttributes($groupId)
    {
        return Attribute::where('attribute_group_id', $groupId)->whereIn('id', $this->getState())->get();
    }

    public function getAttributes($groupId)
    {
        return Attribute::where('attribute_group_id', $groupId)->orderBy('position')->get();
    }
}
