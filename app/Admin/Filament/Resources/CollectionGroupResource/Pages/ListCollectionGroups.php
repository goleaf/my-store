<?php

namespace App\Admin\Filament\Resources\CollectionGroupResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\CollectionGroupResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListCollectionGroups extends BaseListRecords
{
    protected static string $resource = CollectionGroupResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
