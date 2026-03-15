<?php

namespace App\Support\Actions\Collections;

use App\Store\Models\Collection;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;

class DeleteCollection extends DeleteAction
{
    public function setUp(): void
    {
        parent::setUp();

        $this->before(function ($record, $action) {
            if ($record->children()->exists()) {
                Notification::make()
                    ->title(__('admin::actions.collections.delete.notifications.cannot_delete.title'))
                    ->body(__('admin::actions.collections.delete.notifications.cannot_delete.body'))
                    ->danger()
                    ->send();

                $action->halt();
            }
        });

        $this->record(function (array $arguments) {
            return Collection::find($arguments['id']);
        });

        $this->label(
            __('admin::actions.collections.delete.label')
        );
    }
}
