<?php

namespace App\Filament\Resources\TaxClassResource\Pages;

use App\Filament\Resources\TaxClassResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;
use Filament\Notifications\Notification;

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
