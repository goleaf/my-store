<?php

namespace App\Admin\Filament\Resources\TaxClassResource\Pages;

use App\Admin\Filament\Resources\TaxClassResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateTaxClass extends BaseCreateRecord
{
    protected static string $resource = TaxClassResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
