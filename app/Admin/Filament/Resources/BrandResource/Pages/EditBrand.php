<?php

namespace App\Admin\Filament\Resources\BrandResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use App\Admin\Filament\Resources\BrandResource;
use App\Admin\Support\Pages\BaseEditRecord;

class EditBrand extends BaseEditRecord
{
    protected static string $resource = BrandResource::class;

    public function getTitle(): string
    {
        return __('admin::brand.pages.edit.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.edit.title');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record, Actions\DeleteAction $action) {
                    if ($record->products->count() > 0) {
                        Notification::make()
                            ->warning()
                            ->body(__('admin::brand.action.delete.notification.error_protected'))
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }
}
