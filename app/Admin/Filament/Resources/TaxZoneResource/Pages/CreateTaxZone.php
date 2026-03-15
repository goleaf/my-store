<?php

namespace App\Admin\Filament\Resources\TaxZoneResource\Pages;

use App\Admin\Filament\Resources\TaxZoneResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateTaxZone extends BaseCreateRecord
{
    protected static string $resource = TaxZoneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
