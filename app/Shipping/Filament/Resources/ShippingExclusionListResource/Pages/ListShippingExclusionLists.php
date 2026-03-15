<?php

namespace App\Shipping\Filament\Resources\ShippingExclusionListResource\Pages;

use App\Shipping\Filament\Resources\ShippingExclusionListResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;

class ListShippingExclusionLists extends BaseListRecords
{
    protected static string $resource = ShippingExclusionListResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->form([
                ShippingExclusionListResource::getNameFormComponent(),
            ]),
        ];
    }
}
