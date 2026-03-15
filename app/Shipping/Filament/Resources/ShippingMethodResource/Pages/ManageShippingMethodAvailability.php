<?php

namespace App\Shipping\Filament\Resources\ShippingMethodResource\Pages;

use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use App\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use App\Admin\Support\Pages\BaseManageRelatedRecords;
use App\Shipping\Filament\Resources\ShippingMethodResource;

class ManageShippingMethodAvailability extends BaseManageRelatedRecords
{
    protected static string $resource = ShippingMethodResource::class;

    protected static string $relationship = 'customerGroups';

    public function getTitle(): string
    {

        return __('lunarpanel.shipping::shippingmethod.pages.availability.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::availability');
    }

    public static function getNavigationLabel(): string
    {
        return __('lunarpanel.shipping::shippingmethod.pages.availability.label');
    }

    public function getRelationManagers(): array
    {
        return [
            RelationGroup::make('Availability', [
                CustomerGroupRelationManager::make([
                    'description' => __('lunarpanel.shipping::relationmanagers.shipping_methods.customer_groups.description'),
                ]),
            ]),
        ];
    }
}
