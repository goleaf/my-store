<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductVariantResource\Pages;
use App\Store\Models\Contracts\ProductVariant as ProductVariantContract;
use App\Store\Models\TaxClass;
use App\Support\Forms\Components\Attributes;
use App\Support\Forms\Components\TextInputSelectAffix;
use App\Support\Resources\BaseResource;
use Cartalyst\Converter\Laravel\Facades\Converter;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Schemas\Schema;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductVariantResource extends BaseResource
{
    protected static ?string $permission = 'catalog:manage-products';

    protected static ?string $model = ProductVariantContract::class;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('admin::productvariant.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::productvariant.plural_label');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return false;
    }

    public static function getDefaultSubNavigation(): array
    {
        return [
            ProductVariantResource\Pages\EditProductVariant::class,
            ProductVariantResource\Pages\ManageVariantMedia::class,
            ProductVariantResource\Pages\ManageVariantPricing::class,
            ProductVariantResource\Pages\ManageVariantIdentifiers::class,
            ProductVariantResource\Pages\ManageVariantInventory::class,
            ProductVariantResource\Pages\ManageVariantShipping::class,
        ];
    }

    public static function getBaseBreadcrumbs(ProductVariantContract $productVariant): array
    {
        return [
            ProductResource::getUrl('edit', [
                'record' => $productVariant->product,
            ]) => $productVariant->product->attr('name'),
            ProductResource::getUrl('variants', [
                'record' => $productVariant->product,
            ]) => 'Variants',
            ProductVariantResource::getUrl('edit', [
                'record' => $productVariant,
            ]) => $productVariant->sku,
        ];
    }

    public static function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                static::getAttributeDataFormComponent(),
            ])
            ->columns(1);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getSkuFormComponent(),
        ];
    }

    public static function getSkuFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('sku');
    }

    public static function getGtinFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('gtin')->label(
            __('admin::productvariant.form.gtin.label')
        );
    }

    public static function getMpnFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('mpn')->label(
            __('admin::productvariant.form.mpn.label')
        );
    }

    public static function getEanFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('ean')->label(
            __('admin::productvariant.form.ean.label')
        );
    }

    public static function getStockFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('stock')
            ->label(
                __('admin::productvariant.form.stock.label')
            )->numeric();
    }

    public static function getBackorderFormComponent(): Forms\Components\TextInput
    {
        return
            Forms\Components\TextInput::make('backorder')
                ->label(
                    __('admin::productvariant.form.backorder.label')
                )->numeric();
    }

    public static function getPurchasableFormComponent(): Forms\Components\Select
    {
        return Forms\Components\Select::make('purchasable')
            ->options([
                'always' => __('admin::productvariant.form.purchasable.options.always'),
                'in_stock' => __('admin::productvariant.form.purchasable.options.in_stock'),
                'in_stock_or_on_backorder' => __('admin::productvariant.form.purchasable.options.in_stock_or_on_backorder'),
            ])
            ->label(
                __('admin::productvariant.form.purchasable.label')
            );
    }

    public static function getUnitQtyFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('unit_quantity')
            ->label(
                __('admin::productvariant.form.unit_quantity.label')
            )->helperText(
                __('admin::productvariant.form.unit_quantity.helper_text')
            )->numeric();
    }

    public static function getQuantityIncrementFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('quantity_increment')
            ->label(
                __('admin::productvariant.form.quantity_increment.label')
            )->helperText(
                __('admin::productvariant.form.quantity_increment.helper_text')
            )->numeric();
    }

    public static function getMinQuantityFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('min_quantity')
            ->label(
                __('admin::productvariant.form.min_quantity.label')
            )->helperText(
                __('admin::productvariant.form.min_quantity.helper_text')
            )->numeric();
    }

    public static function getTaxClassIdFormComponent(): Forms\Components\Select
    {
        return Forms\Components\Select::make('tax_class_id')
            ->label(
                __('admin::productvariant.form.tax_class_id.label')
            )
            ->options(
                TaxClass::all()->pluck('name', 'id')
            )->required();
    }

    public static function getTaxRefFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('tax_ref')
            ->label(
                __('admin::product.pages.pricing.form.tax_ref.label')
            )->helperText(
                __('admin::product.pages.pricing.form.tax_ref.helper_text')
            );
    }

    public static function getShippableFormComponent(): Forms\Components\Toggle
    {
        return Forms\Components\Toggle::make('shippable')->label(
            __('admin::productvariant.form.shippable.label')
        )->columnSpan(2);
    }

    public static function getMeasurements($key = null): array
    {
        $measurements = Converter::getMeasurements();

        return collect(
            array_keys($measurements[$key] ?? [])
        )->mapWithKeys(
            fn ($value) => [$value => $value]
        )->toArray();
    }

    public static function getLengthFormComponent(): TextInputSelectAffix
    {
        return TextInputSelectAffix::make('length_value')
            ->label(
                __('admin::productvariant.form.length_value.label')
            )
            ->numeric()
            ->select(
                fn () => Forms\Components\Select::make('length_unit')
                    ->options(
                        static::getMeasurements('length')
                    )
                    ->label(
                        __('admin::productvariant.form.length_unit.label')
                    )->selectablePlaceholder(false)
            );
    }

    public static function getWidthFormComponent(): TextInputSelectAffix
    {
        return TextInputSelectAffix::make('width_value')
            ->label(
                __('admin::productvariant.form.width_value.label')
            )
            ->numeric()
            ->select(
                fn () => Forms\Components\Select::make('width_unit')
                    ->options(
                        static::getMeasurements('length')
                    )
                    ->label(
                        __('admin::productvariant.form.width_unit.label')
                    )->selectablePlaceholder(false)
            );
    }

    public static function getHeightFormComponent(): TextInputSelectAffix
    {
        return TextInputSelectAffix::make('height_value')
            ->label(
                __('admin::productvariant.form.height_value.label')
            )
            ->numeric()
            ->select(
                fn () => Forms\Components\Select::make('height_unit')
                    ->options(
                        static::getMeasurements('length')
                    )
                    ->label(
                        __('admin::productvariant.form.height_unit.label')
                    )->selectablePlaceholder(false)
            );
    }

    public static function getWeightFormComponent(): TextInputSelectAffix
    {
        return TextInputSelectAffix::make('weight_value')
            ->label(
                __('admin::productvariant.form.weight_value.label')
            )
            ->numeric()
            ->select(
                fn () => Forms\Components\Select::make('weight_unit')
                    ->options(
                        static::getMeasurements('weight')
                    )
                    ->label(
                        __('admin::productvariant.form.weight_unit.label')
                    )->selectablePlaceholder(false)
            );
    }

    public static function getVariantSwitcherWidget(Model $record): Action
    {
        return Action::make('switch_variant')
            ->label(
                __('admin::widgets.variant_switcher.label')
            )
            ->modalContent(function () use ($record) {
                return view('admin::actions.switch-variant', [
                    'record' => $record->product,
                ]);
            })
            ->slideOver();
    }

    protected static function getAttributeDataFormComponent(): Component
    {
        return Attributes::make();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([])
            ->actions([])
            ->bulkActions([])
            ->selectCurrentPageOnly()
            ->deferLoading();
    }

    protected static function getTableColumns(): array
    {
        return [

        ];
    }

    public static function getDefaultRelations(): array
    {
        return [];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => ProductVariantResource\Pages\ListProductVariants::route('/'),
            'edit' => ProductVariantResource\Pages\EditProductVariant::route('/{record}/edit'),
            'pricing' => ProductVariantResource\Pages\ManageVariantPricing::route('/{record}/pricing'),
            'media' => ProductVariantResource\Pages\ManageVariantMedia::route('/{record}/media'),
            'identifiers' => ProductVariantResource\Pages\ManageVariantIdentifiers::route('/{record}/identifiers'),
            'inventory' => ProductVariantResource\Pages\ManageVariantInventory::route('/{record}/inventory'),
            'shipping' => ProductVariantResource\Pages\ManageVariantShipping::route('/{record}/shipping'),
        ];
    }
}
