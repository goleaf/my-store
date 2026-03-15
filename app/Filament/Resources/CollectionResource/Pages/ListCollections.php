<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use App\Support\Pages\BaseListRecords;

class ListCollections extends BaseListRecords
{
    protected static string $resource = CollectionResource::class;

    public function mount(): void
    {
        abort(404);
    }

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }
}
