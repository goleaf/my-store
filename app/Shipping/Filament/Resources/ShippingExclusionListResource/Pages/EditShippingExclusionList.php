<?php

namespace App\Shipping\Filament\Resources\ShippingExclusionListResource\Pages;

use App\Shipping\Filament\Resources\ShippingExclusionListResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;

class EditShippingExclusionList extends BaseEditRecord
{
    protected static string $resource = ShippingExclusionListResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
