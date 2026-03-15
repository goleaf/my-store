<?php

namespace App\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use Filament\Actions;
use App\Admin\Support\Pages\BaseListRecords;
use App\Shipping\Filament\Resources\ShippingZoneResource;

class ListShippingZones extends BaseListRecords
{
    protected static string $resource = ShippingZoneResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->form([
                ShippingZoneResource::getNameFormComponent(),
                ShippingZoneResource::getTypeFormComponent(),
            ]),
        ];
    }
}
