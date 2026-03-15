<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components as SchemaComponents;

class ListDiscounts extends BaseListRecords
{
    protected static string $resource = DiscountResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->form([
                SchemaComponents\Group::make([
                    DiscountResource::getNameFormComponent(),
                    DiscountResource::getHandleFormComponent(),
                ])->columns(2),
                SchemaComponents\Group::make([
                    DiscountResource::getStartsAtFormComponent(),
                    DiscountResource::getEndsAtFormComponent(),
                ])->columns(2),
                DiscountResource::getDiscountTypeFormComponent(),
            ]),
        ];
    }
}
