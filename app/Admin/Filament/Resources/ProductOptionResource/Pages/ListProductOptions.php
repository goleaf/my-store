<?php

namespace App\Admin\Filament\Resources\ProductOptionResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\ProductOptionResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListProductOptions extends BaseListRecords
{
    protected static string $resource = ProductOptionResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
