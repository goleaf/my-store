<?php

namespace App\Admin\Filament\Resources\LanguageResource\Pages;

use App\Admin\Filament\Resources\LanguageResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateLanguage extends BaseCreateRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
