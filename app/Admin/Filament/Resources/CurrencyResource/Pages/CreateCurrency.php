<?php

namespace App\Admin\Filament\Resources\CurrencyResource\Pages;

use App\Admin\Filament\Resources\CurrencyResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateCurrency extends BaseCreateRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
