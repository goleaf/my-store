<?php

namespace App\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use App\Shipping\Filament\Resources\ShippingZoneResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;

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
