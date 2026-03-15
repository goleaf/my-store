<?php

namespace App\Admin\Filament\Resources\TaxRateResource\Pages;

use App\Admin\Filament\Resources\TaxRateResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateTaxRate extends BaseCreateRecord
{
    protected static string $resource = TaxRateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
