<?php

namespace App\Admin\Filament\Resources\BrandResource\Pages;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Admin\Filament\Resources\BrandResource;
use App\Admin\Filament\Resources\ProductResource;
use App\Admin\Support\Pages\BaseManageRelatedRecords;
use App\Store\Models\Contracts\Product as ProductContract;
use App\Store\Models\Product;

class ManageBrandProducts extends BaseManageRelatedRecords
{
    protected static string $resource = BrandResource::class;

    protected static string $relationship = 'products';

    public function getTitle(): string
    {

        return __('admin::brand.pages.products.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::products');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::brand.pages.products.label');
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            ProductResource::getNameTableColumn()->searchable()
                ->url(function (Model $record) {
                    return ProductResource::getUrl('edit', [
                        'record' => $record->getKey(),
                    ]);
                }),
            ProductResource::getSkuTableColumn(),
        ])->actions([
            DetachAction::make()
                ->action(function (Model $record) {
                    $record->update([
                        'brand_id' => null,
                    ]);

                    Notification::make()
                        ->success()
                        ->body(__('admin::brand.pages.products.actions.detach.notification.success'))
                        ->send();
                }),
        ])->headerActions([
            AttachAction::make()
                ->label(
                    __('admin::brand.pages.products.actions.attach.label')
                )
                ->form([
                    Forms\Components\Select::make('recordId')
                        ->label(
                            __('admin::brand.pages.products.actions.attach.form.record_id.label')
                        )
                        ->required()
                        ->searchable()
                        ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                            return Product::search($search)
                                ->get()
                                ->mapWithKeys(fn (ProductContract $record): array => [$record->getKey() => $record->translateAttribute('name')])
                                ->all();
                        }),
                ])
                ->action(function (array $arguments, array $data) {
                    Product::where('id', '=', $data['recordId'])
                        ->update([
                            'brand_id' => $this->getRecord()->id,
                        ]);

                    Notification::make()
                        ->success()
                        ->body(__('admin::brand.pages.products.actions.attach.notification.success'))
                        ->send();
                }),
        ]);
    }
}
