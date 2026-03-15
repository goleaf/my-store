<?php

namespace App\Admin\Filament\Resources\CustomerGroupResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use App\Admin\Filament\Resources\CustomerGroupResource;
use App\Admin\Support\Pages\BaseEditRecord;

class EditCustomerGroup extends BaseEditRecord
{
    protected static string $resource = CustomerGroupResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record, Actions\DeleteAction $action) {
                    if ($record->customers->count() > 0) {
                        Notification::make()
                            ->warning()
                            ->body(__('admin::customergroup.action.delete.notification.error_protected'))
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
