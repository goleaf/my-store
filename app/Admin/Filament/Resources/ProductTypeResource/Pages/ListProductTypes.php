<?php

namespace App\Admin\Filament\Resources\ProductTypeResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\ProductTypeResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListProductTypes extends BaseListRecords
{
    protected static string $resource = ProductTypeResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
