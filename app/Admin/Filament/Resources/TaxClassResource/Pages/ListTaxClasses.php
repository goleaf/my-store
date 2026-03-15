<?php

namespace App\Admin\Filament\Resources\TaxClassResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\TaxClassResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListTaxClasses extends BaseListRecords
{
    protected static string $resource = TaxClassResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
