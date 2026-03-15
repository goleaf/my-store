<?php

namespace App\Admin\Filament\Resources\ChannelResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\ChannelResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListChannels extends BaseListRecords
{
    protected static string $resource = ChannelResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
