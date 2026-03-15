<?php

namespace App\Admin\Filament\Resources\LanguageResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\LanguageResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListLanguages extends BaseListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
