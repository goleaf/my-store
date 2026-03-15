<?php

namespace App\Admin\Filament\Resources\BrandResource\Pages;

use App\Admin\Filament\Resources\BrandResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateBrand extends BaseCreateRecord
{
    protected static string $resource = BrandResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
