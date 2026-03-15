<?php

namespace App\Admin\Filament\Resources\OrderResource\Pages;

use App\Admin\Filament\Resources\OrderResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateOrder extends BaseCreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
