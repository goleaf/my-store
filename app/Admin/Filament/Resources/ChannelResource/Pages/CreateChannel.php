<?php

namespace App\Admin\Filament\Resources\ChannelResource\Pages;

use App\Admin\Filament\Resources\ChannelResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateChannel extends BaseCreateRecord
{
    protected static string $resource = ChannelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
