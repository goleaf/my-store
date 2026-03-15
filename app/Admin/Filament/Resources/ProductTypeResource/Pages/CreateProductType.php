<?php

namespace App\Admin\Filament\Resources\ProductTypeResource\Pages;

use App\Admin\Filament\Resources\ProductTypeResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateProductType extends BaseCreateRecord
{
    protected static string $resource = ProductTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
