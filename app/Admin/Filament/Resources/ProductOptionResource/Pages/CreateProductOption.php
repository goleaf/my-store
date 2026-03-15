<?php

namespace App\Admin\Filament\Resources\ProductOptionResource\Pages;

use App\Admin\Filament\Resources\ProductOptionResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateProductOption extends BaseCreateRecord
{
    protected static string $resource = ProductOptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['shared'] = true;

        return $data;
    }
}
