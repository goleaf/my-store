<?php

namespace App\Admin\Filament\Resources\CustomerGroupResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\CustomerGroupResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListCustomerGroups extends BaseListRecords
{
    protected static string $resource = CustomerGroupResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
