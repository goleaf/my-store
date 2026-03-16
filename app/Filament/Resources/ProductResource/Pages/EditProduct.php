<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Base\Enums\ProductStatus;
use App\Filament\Resources\ProductResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms;
use Filament\Support\Facades\FilamentIcon;

class EditProduct extends BaseEditRecord
{
    protected static string $resource = ProductResource::class;

    public static bool $formActionsAreSticky = true;

    public function getTitle(): string
    {
        return __('admin::product.pages.edit.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.edit.title');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::basic-information');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\EditAction::make('update_status')
                ->label(
                    __('admin::product.actions.edit_status.label')
                )
                ->modalHeading(
                    __('admin::product.actions.edit_status.heading')
                )
                ->record(
                    $this->record
                )->form([
                    Forms\Components\Radio::make('status')->options(ProductStatus::options())
                        ->descriptions([
                            ProductStatus::Published->value => __('admin::product.form.status.options.published.description'),
                            ProductStatus::Draft->value => __('admin::product.form.status.options.draft.description'),
                        ])->live(),
                ]),
            Actions\DeleteAction::make(),
            ForceDeleteAction::make()
                ->databaseTransaction(),
            Actions\RestoreAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
