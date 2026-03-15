<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Support\Pages\BaseManageRelatedRecords;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class ManageProductVariants extends BaseManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'variants';

    protected function getDefaultHeaderWidgets(): array
    {
        return [
            ProductResource\Widgets\ProductOptionsWidget::class,
        ];
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-variants');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return config('store.panel.enable_variants', true);
    }

    public static function canAccess(array $parameters = []): bool
    {
        if (! config('store.panel.enable_variants', true)) {
            return false;
        }

        return parent::canAccess($parameters);
    }

    public function getTitle(): string
    {
        return __('admin::product.pages.variants.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.variants.label');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('sku'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make(),
                //                Actions\AssociateAction::make(),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                //                Actions\DissociateAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    //                    Actions\DissociateBulkAction::make(),
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
