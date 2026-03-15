<?php

namespace App\Admin\Filament\Resources\TaxClassResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use App\Admin\Filament\Resources\TaxClassResource;
use App\Admin\Support\Pages\BaseEditRecord;

class EditTaxClass extends BaseEditRecord
{
    protected static string $resource = TaxClassResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record, $action) {
                    if ($record->productVariants()->exists()) {
                        Notification::make()
                            ->title(__('admin::taxclass.delete.error.title'))
                            ->body(__('admin::taxclass.delete.error.body'))
                            ->danger()
                            ->send();

                        $action->halt();
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
