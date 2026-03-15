<?php

namespace App\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use App\Admin\Filament\Resources\ProductResource;
use App\Admin\Support\Pages\BaseManageRelatedRecords;

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
                Tables\Actions\CreateAction::make(),
                //                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                //                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
