<?php

namespace App\Filament\Resources\HomeHeroResource\Pages;

use App\Filament\Resources\HomeHeroResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;

class ListHomeHeroes extends BaseListRecords
{
    protected static string $resource = HomeHeroResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
