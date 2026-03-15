<?php

namespace App\Filament\Resources;

use App\Admin\Filament\Resources\ProductTypeResource\Pages;
use App\Store\Models\Contracts\ProductType as ProductTypeContract;
use App\Store\Models\Product;
use App\Store\Models\ProductVariant;
use App\Support\Forms\Components\AttributeSelector;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class ProductTypeResource extends BaseResource
{
    protected static ?string $permission = 'catalog:manage-products';

    protected static ?string $model = ProductTypeContract::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?int $navigationSort = 2;

    public static function getLabel(): string
    {
        return __('admin::producttype.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::producttype.plural_label');
    }

    public static function getNavigationParentItem(): ?string
    {
        return __('admin::product.plural_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.catalog');
    }

    public static function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make()->schema(
                    static::getMainFormComponents()
                ),
                SchemaComponents\Tabs::make('Attributes')->tabs([
                    SchemaComponents\Tabs\Tab::make(__('admin::producttype.tabs.product_attributes.label'))
                        ->schema([
                            AttributeSelector::make('mappedAttributes')
                                ->withType(Product::morphName())
                                ->relationship(name: 'mappedAttributes')
                                ->label('')
                                ->columnSpan(2),
                        ]),
                    SchemaComponents\Tabs\Tab::make(__('admin::producttype.tabs.variant_attributes.label'))
                        ->schema([
                            AttributeSelector::make('mappedAttributes')
                                ->withType(ProductVariant::morphName())
                                ->relationship(name: 'mappedAttributes')
                                ->label('')
                                ->columnSpan(2),
                        ])->visible(
                            config('store.panel.enable_variants', true)
                        ),

                ])->columnSpan(2),
            ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin::producttype.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('admin::producttype.table.name.label')),
            Tables\Columns\TextColumn::make('products_count')
                ->counts('products')
                ->formatStateUsing(
                    fn ($state) => number_format($state, 0)
                )
                ->label(__('admin::producttype.table.products_count.label')),
            Tables\Columns\TextColumn::make('product_attributes_count')
                ->counts('productAttributes')
                ->formatStateUsing(
                    fn ($state) => number_format($state, 0)
                )
                ->label(__('admin::producttype.table.product_attributes_count.label')),
            Tables\Columns\TextColumn::make('variant_attributes_count')
                ->counts('variantAttributes')
                ->formatStateUsing(
                    fn ($state) => number_format($state, 0)
                )
                ->label(__('admin::producttype.table.variant_attributes_count.label'))
                ->visible(
                    config('store.panel.enable_variants', true)
                ),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => ProductTypeResource\Pages\ListProductTypes::route('/'),
            'create' => ProductTypeResource\Pages\CreateProductType::route('/create'),
            'edit' => ProductTypeResource\Pages\EditProductType::route('/{record}/edit'),
        ];
    }
}
