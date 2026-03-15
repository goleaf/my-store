<?php

namespace App\Admin\Support\Actions\Collections;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use App\Admin\Support\Forms\Components\TranslatedText;
use App\Store\Facades\DB;
use App\Store\Models\Attribute;
use App\Store\Models\Collection;

class CreateRootCollection extends CreateAction
{
    public function setUp(): void
    {
        parent::setUp();

        $this->action(function (array $arguments, Form $form): void {
            $model = $this->getModel();

            DB::beginTransaction();

            $record = $this->process(function (array $data) {
                $attribute = Attribute::whereHandle('name')->whereAttributeType(
                    Collection::morphName()
                )->first()->type;

                return Collection::create([
                    'collection_group_id' => $data['collection_group_id'],
                    'attribute_data' => [
                        'name' => new $attribute($data['name']),
                    ],
                ]);
            });

            DB::commit();

            $this->record($record);
            $form->model($record);

            if ($arguments['another'] ?? false) {
                $this->callAfter();
                $this->sendSuccessNotification();

                $this->record(null);

                // Ensure that the form record is anonymized so that relationships aren't loaded.
                $form->model($model);

                $form->fill();

                $this->halt();

                return;
            }

            $this->success();
        });

        $attribute = Attribute::where('attribute_type', '=', Collection::morphName())
            ->where('handle', '=', 'name')->first();

        $formInput = TextInput::class;

        if ($attribute?->type == \App\Store\FieldTypes\TranslatedText::class) {
            $formInput = TranslatedText::class;
        }

        $this->form([
            $formInput::make('name')
                ->label(__('admin::collection.form.name.label'))
                ->required(),
        ]);

        $this->label(
            __('admin::actions.collections.create_root.label')
        );

        $this->modalHeading(
            __('admin::actions.collections.create_root.label')
        );
    }
}
