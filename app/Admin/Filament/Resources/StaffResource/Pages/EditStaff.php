<?php

namespace App\Admin\Filament\Resources\StaffResource\Pages;

use Filament\Actions;
use App\Admin\Filament\Resources\StaffResource;
use App\Admin\Support\Pages\BaseEditRecord;

class EditStaff extends BaseEditRecord
{
    protected static string $resource = StaffResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
