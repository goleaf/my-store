<?php

namespace App\Filament\Resources\CollectionGroupResource\Pages;

use App\Filament\Resources\CollectionGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCollectionGroup extends CreateRecord
{
    protected static string $resource = CollectionGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
