<?php

namespace App\Admin\Filament\Resources\TagResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\TagResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListTags extends BaseListRecords
{
    protected static string $resource = TagResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
