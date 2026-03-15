<?php

namespace App\Admin\Filament\Resources\BrandResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\BrandResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListBrands extends BaseListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
