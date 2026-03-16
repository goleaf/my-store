<?php

namespace App\Filament\Resources\HomeSectionResource\Pages;

use App\Filament\Resources\HomeSectionResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;

class ListHomeSections extends BaseListRecords
{
    protected static string $resource = HomeSectionResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
