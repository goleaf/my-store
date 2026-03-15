<?php

namespace App\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use App\Shipping\Filament\Resources\ShippingZoneResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;

class EditShippingZone extends BaseEditRecord
{
    protected static string $resource = ShippingZoneResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::resources/pages/edit-record.title', [
            'label' => __('lunarpanel.shipping::shippingzone.label'),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
