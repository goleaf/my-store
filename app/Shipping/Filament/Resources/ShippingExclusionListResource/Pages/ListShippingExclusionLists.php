<?php

namespace App\Shipping\Filament\Resources\ShippingExclusionListResource\Pages;

use Filament\Actions;
use App\Admin\Support\Pages\BaseListRecords;
use App\Shipping\Filament\Resources\ShippingExclusionListResource;

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
