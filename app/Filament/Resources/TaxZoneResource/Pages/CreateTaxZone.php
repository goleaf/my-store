<?php

namespace App\Filament\Resources\TaxZoneResource\Pages;

use App\Filament\Resources\TaxZoneResource;
use App\Support\Pages\BaseCreateRecord;

class CreateTaxZone extends BaseCreateRecord
{
    protected static string $resource = TaxZoneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
