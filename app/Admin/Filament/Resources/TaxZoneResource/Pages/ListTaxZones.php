<?php

namespace App\Admin\Filament\Resources\TaxZoneResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\TaxZoneResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListTaxZones extends BaseListRecords
{
    protected static string $resource = TaxZoneResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
