<?php

namespace App\Filament\Resources\TaxZoneResource\Pages;

use App\Filament\Resources\TaxZoneResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;

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
