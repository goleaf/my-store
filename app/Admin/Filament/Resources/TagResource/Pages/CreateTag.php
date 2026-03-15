<?php

namespace App\Admin\Filament\Resources\TagResource\Pages;

use App\Admin\Filament\Resources\TagResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateTag extends BaseCreateRecord
{
    protected static string $resource = TagResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
