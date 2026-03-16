<?php

namespace App\Filament\Resources\HomeHeroResource\Pages;

use App\Filament\Resources\HomeHeroResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;

class EditHomeHero extends BaseEditRecord
{
    protected static string $resource = HomeHeroResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
