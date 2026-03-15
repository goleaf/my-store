<?php

namespace App\Admin\Filament\Resources\TaxRateResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\TaxRateResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListTaxRates extends BaseListRecords
{
    protected static string $resource = TaxRateResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
