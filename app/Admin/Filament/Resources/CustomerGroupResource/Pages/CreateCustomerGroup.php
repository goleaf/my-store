<?php

namespace App\Admin\Filament\Resources\CustomerGroupResource\Pages;

use App\Admin\Filament\Resources\CustomerGroupResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateCustomerGroup extends BaseCreateRecord
{
    protected static string $resource = CustomerGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
