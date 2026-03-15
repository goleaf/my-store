<?php

namespace App\Admin\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\CustomerResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListCustomers extends BaseListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
