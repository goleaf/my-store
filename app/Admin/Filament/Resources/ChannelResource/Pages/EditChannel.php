<?php

namespace App\Admin\Filament\Resources\ChannelResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\ChannelResource;
use App\Admin\Support\Pages\BaseEditRecord;

class EditChannel extends BaseEditRecord
{
    protected static string $resource = ChannelResource::class;

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
