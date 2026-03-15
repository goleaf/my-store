<?php

namespace App\Support\Tables\Actions\Collections;

use App\Support\Actions\Traits\CreatesChildCollections;
use App\Support\Forms\Components\TranslatedText;
use Filament\Actions\CreateAction;
use Filament\Tables\Table;
class CreateChildCollection extends CreateAction
{
    use CreatesChildCollections;

    public function setUp(): void
    {
        parent::setUp();

        $this->action(function (array $arguments, array $data, Table $table): void {
            $this->createChildCollection(
                $table->getRelationship()->getParent(),
                $data['name']
            );

            $this->success();
        });

        $this->form([
            TranslatedText::make('name')->required(),
        ]);

        $this->createAnother(false);

        $this->label(
            __('admin::collection.pages.children.actions.create_child.label')
        );
    }
}
