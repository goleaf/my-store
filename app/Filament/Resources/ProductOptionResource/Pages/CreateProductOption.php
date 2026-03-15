<?php

namespace App\Filament\Resources\ProductOptionResource\Pages;

use App\Filament\Resources\ProductOptionResource;
use App\Support\Pages\BaseCreateRecord;

class CreateProductOption extends BaseCreateRecord
{
    protected static string $resource = ProductOptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['shared'] = true;

        return $data;
    }
}
