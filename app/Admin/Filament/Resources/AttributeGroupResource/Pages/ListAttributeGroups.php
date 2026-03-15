<?php

namespace App\Admin\Filament\Resources\AttributeGroupResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\AttributeGroupResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListAttributeGroups extends BaseListRecords
{
    protected static string $resource = AttributeGroupResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
