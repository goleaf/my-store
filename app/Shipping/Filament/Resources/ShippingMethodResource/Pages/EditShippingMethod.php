<?php

namespace App\Shipping\Filament\Resources\ShippingMethodResource\Pages;

use Filament\Actions;
use App\Admin\Support\Pages\BaseEditRecord;
use App\Shipping\Filament\Resources\ShippingMethodResource;

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
            'label' => __('lunarpanel.shipping::shippingmethod.label'),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
