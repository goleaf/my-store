<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;
use Filament\Support\Colors\Color;

class ListStaff extends BaseListRecords
{
    protected static string $resource = StaffResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\Action::make('access-control')
                ->label(__('admin::staff.action.acl.label'))
                ->color(Color::Lime)
                ->url(fn () => StaffResource::getUrl('acl')),
            Actions\CreateAction::make(),
        ];
    }
}
