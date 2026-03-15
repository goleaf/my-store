<?php

namespace App\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use Filament\Actions;
use App\Admin\Support\Pages\BaseEditRecord;
use App\Shipping\Filament\Resources\ShippingZoneResource;

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
