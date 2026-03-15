<?php

namespace App\Admin\Filament\Resources\StaffResource\Pages;

use Filament\Actions;
use Filament\Support\Colors\Color;
use App\Admin\Filament\Resources\StaffResource;
use App\Admin\Support\Pages\BaseListRecords;

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
