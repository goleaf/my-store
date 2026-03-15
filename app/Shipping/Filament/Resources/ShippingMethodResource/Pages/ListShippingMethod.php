<?php

namespace App\Shipping\Filament\Resources\ShippingMethodResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Group;
use App\Admin\Support\Pages\BaseListRecords;
use App\Store\Models\CustomerGroup;
use App\Shipping\Filament\Resources\ShippingMethodResource;
use App\Shipping\Models\ShippingMethod;

class ListShippingMethod extends BaseListRecords
{
    protected static string $resource = ShippingMethodResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->form([
                ShippingMethodResource::getNameFormComponent(),
                Group::make([
                    ShippingMethodResource::getCodeFormComponent(),
                    ShippingMethodResource::getDriverFormComponent(),
                ])->columns(2),
                ShippingMethodResource::getDescriptionFormComponent(),
            ])->after(function (ShippingMethod $shippingMethod) {
                $customerGroups = CustomerGroup::pluck('id')->mapWithKeys(
                    fn ($id) => [$id => ['visible' => true, 'enabled' => true, 'starts_at' => now()]]
                );
                $shippingMethod->customerGroups()->sync($customerGroups);
            }),
        ];
    }
}
