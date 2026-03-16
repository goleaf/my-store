<?php

namespace App\Shipping\Filament\Resources\ShippingMethodResource\Pages;

use App\Shipping\Filament\Resources\ShippingMethodResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;

class EditShippingMethod extends BaseEditRecord
{
    protected static string $resource = ShippingMethodResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::resources/pages/edit-record.title', [
            'label' => __('storepanel.shipping::shippingmethod.label'),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
