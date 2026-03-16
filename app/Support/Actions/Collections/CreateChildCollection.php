<?php

namespace App\Support\Actions\Collections;

use App\Models\Attribute;
use App\Models\Collection;
use App\Support\Actions\Traits\CreatesChildCollections;
use App\Support\Forms\Components\TranslatedText;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use App\FieldTypes;

class CreateChildCollection extends CreateAction
{
    use CreatesChildCollections;

    public function setUp(): void
    {
        parent::setUp();

        $this->action(function (?Model $model, array $arguments, array $data): void {
            $parent = Collection::find($arguments['id']);

            $this->createChildCollection($parent, $data['name']);

            $this->success();
        });

        $attribute = Attribute::where('attribute_type', '=', Collection::morphName())
            ->where('handle', '=', 'name')->first();

        $formInput = TextInput::class;

        if ($attribute?->type == FieldTypes\TranslatedText::class) {
            $formInput = TranslatedText::class;
        }

        $this->form([
            $formInput::make('name')->required(),
        ]);

        $this->label(
            __('admin::actions.collections.create_child.label')
        );

        $this->createAnother(false);

        $this->modalHeading(
            __('admin::actions.collections.create_child.label')
        );
    }
}
