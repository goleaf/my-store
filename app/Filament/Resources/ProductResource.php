<?php

namespace App\Filament\Resources;

use App\Admin\Filament\Resources\ProductResource\Pages;
use App\Admin\Filament\Resources\ProductResource\Widgets\ProductOptionsWidget;
use App\Filament\Components\Shout;
use App\Filament\Resources\ProductResource\RelationManagers\CustomerGroupPricingRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use App\Filament\Widgets\Products\VariantSwitcherTable;
use App\Store\FieldTypes\Text;
use App\Store\FieldTypes\TranslatedText;
use App\Store\Models\Attribute;
use App\Store\Models\Contracts\Product as ProductContract;
use App\Store\Models\Currency;
use App\Store\Models\ProductVariant;
use App\Store\Models\Tag;
use App\Support\Forms\Components\Attributes;
use App\Support\Forms\Components\Tags as TagsComponent;
use App\Support\Forms\Components\TranslatedText as TranslatedTextInput;
use App\Support\RelationManagers\ChannelRelationManager;
use App\Support\RelationManagers\MediaRelationManager;
use App\Support\RelationManagers\PriceRelationManager;
use App\Support\Resources\BaseResource;
use App\Support\Tables\Columns\TranslatedTextColumn;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class ProductResource extends BaseResource
{
    protected static ?string $permission = 'catalog:manage-products';

    protected static ?string $model = ProductContract::class;

    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?int $navigationSort = 1;

    protected static int $globalSearchResultsLimit = 5;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('admin::product.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::product.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::products');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.catalog');
    }

    public static function getDefaultSubNavigation(): array
    {
        return [
            ProductResource\Pages\EditProduct::class,
            ProductResource\Pages\ManageProductAvailability::class,
            ProductResource\Pages\ManageProductMedia::class,
            ProductResource\Pages\ManageProductPricing::class,
            ProductResource\Pages\ManageProductIdentifiers::class,
            ProductResource\Pages\ManageProductInventory::class,
            ProductResource\Pages\ManageProductShipping::class,
            ProductResource\Pages\ManageProductVariants::class,
            ProductResource\Pages\ManageProductUrls::class,
            ProductResource\Pages\ManageProductCollections::class,
            ProductResource\Pages\ManageProductAssociations::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ProductOptionsWidget::class,
            VariantSwitcherTable::class,
        ];
    }

    public static function getDefaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Shout::make('product-status')
                    ->content(
                        __('admin::product.status.unpublished.content')
                    )->type('info')->hidden(
                        fn (Model $record) => $record?->status == 'published'
                    ),
                Shout::make('product-customer-groups')
                    ->content(
                        __('admin::product.status.availability.customer_groups')
                    )->type('warning')->hidden(function (Model $record) {
                        return $record->customerGroups()->where('enabled', true)->count();
                    }),
                Shout::make('product-channels')
                    ->content(
                        __('admin::product.status.availability.channels')
                    )->type('warning')->hidden(function (Model $record) {
                        return $record->channels()->where('enabled', true)->count();
                    }),
                SchemaComponents\Section::make()
                    ->schema(
                        static::getMainFormComponents(),
                    ),
                static::getAttributeDataFormComponent(),
                static::getVariantAttributeDataFormComponent(),
            ])
            ->columns(1);
    }


    protected static function getMainFormComponents(): array
    {
        return [
            static::getBrandFormComponent(),
            static::getProductTypeFormComponent(),
            static::getTagsFormComponent(),
        ];
    }

    public static function getSkuValidation(): array
    {
        return static::callStaticStoreHook('extendSkuValidation', [
            'required' => true,
            'unique' => true,
        ]);
    }

    public static function getSkuFormComponent(): Component
    {
        $validation = static::getSkuValidation();

        $input = Forms\Components\TextInput::make('sku')
            ->label(__('admin::product.form.sku.label'))
            ->required($validation['required'] ?? false);

        if ($validation['unique'] ?? false) {
            $input->unique(fn () => (new ProductVariant)->getTable());
        }

        return $input;
    }

    public static function getBasePriceFormComponent(): Component
    {
        $currency = Currency::getDefault();

        return Forms\Components\TextInput::make('base_price')->numeric()->prefix(
            $currency->code
        )->rules([
            'min:'.(1 / $currency->factor),
            "decimal:0,{$currency->decimal_places}",
        ])->required();
    }

    public static function getBaseNameFormComponent(): Component
    {
        $nameType = Attribute::whereHandle('name')
            ->whereAttributeType(
                static::getModel()::morphName()
            )
            ->first()?->type ?: TranslatedText::class;

        $component = TranslatedTextInput::make('name');

        if ($nameType == Text::class) {
            $component = Forms\Components\TextInput::make('name');
        }

        return $component->label(__('admin::product.form.name.label'))->required();
    }

    protected static function getBrandFormComponent(): Component
    {
        return Forms\Components\Select::make('brand_id')
            ->label(__('admin::product.form.brand.label'))
            ->relationship('brand', 'name')
            ->searchable()
            ->preload()
            ->createOptionForm([
                Forms\Components\TextInput::make('name')
                    ->required(),
            ]);
    }

    public static function getProductTypeFormComponent(): Component
    {
        return Forms\Components\Select::make('product_type_id')
            ->label(__('admin::product.form.producttype.label'))
            ->relationship('productType', 'name')
            ->searchable()
            ->preload()
            ->live()
            ->required();
    }

    protected static function getTagsFormComponent(): Component
    {
        return TagsComponent::make('tags')
            ->suggestions(Tag::all()->pluck('value')->all())
            ->splitKeys(['Tab', ','])
            ->label(__('admin::product.form.tags.label'))
            ->helperText(__('admin::product.form.tags.helper_text'));
    }

    protected static function getAttributeDataFormComponent(): Component
    {
        return Attributes::make();
    }

    protected static function getVariantAttributeDataFormComponent(): Component
    {
        return Attributes::make()
            ->using(ProductVariant::class)
            ->relationship('variant')
            ->hidden(fn (ProductContract $record) => $record->hasVariants);
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->label(__('admin::product.table.brand.label'))
                    ->relationship('brand', 'name'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly()
            ->deferLoading();
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('status')
                ->label(__('admin::product.table.status.label'))
                ->badge()
                ->getStateUsing(
                    fn (Model $record) => $record->deleted_at ? 'deleted' : $record->status
                )
                ->formatStateUsing(fn ($state) => __('admin::product.table.status.states.'.$state))
                ->color(fn (string $state): string => match ($state) {
                    'draft' => 'warning',
                    'published' => 'success',
                    'deleted' => 'danger',
                    default => 'primary',
                }),
            SpatieMediaLibraryImageColumn::make('thumbnail')
                ->collection(config('store.media.collection'))
                ->conversion('small')
                ->filterMediaUsing(fn ($media) => $media->where('custom_properties.primary', true)->count() ? $media->where('custom_properties.primary', true) : $media)
                ->limit(1)
                ->square()
                ->label(''),
            static::getNameTableColumn(),
            Tables\Columns\TextColumn::make('brand.name')
                ->label(__('admin::product.table.brand.label'))
                ->toggleable()
                ->searchable(),
            static::getSkuTableColumn(),
            Tables\Columns\TextColumn::make('variants_sum_stock')
                ->label(__('admin::product.table.stock.label'))
                ->sum('variants', 'stock'),
            Tables\Columns\TextColumn::make('productType.name')
                ->label(__('admin::product.table.producttype.label'))
                ->limit(30)
                ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                    $state = $column->getState();

                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }

                    // Only render the tooltip if the column contents exceeds the length limit.
                    return $state;
                })
                ->toggleable(),
        ];
    }

    public static function getNameTableColumn(): Tables\Columns\Column
    {
        return TranslatedTextColumn::make('attribute_data.name')
            ->attributeData()
            ->limitedTooltip()
            ->limit(50)
            ->label(__('admin::product.table.name.label'))
            ->searchable();
    }

    public static function getSkuTableColumn(): Tables\Columns\Column
    {
        return Tables\Columns\TextColumn::make('variants.sku')
            ->label(__('admin::product.table.sku.label'))
            ->tooltip(function (Tables\Columns\TextColumn $column, Model $record): ?string {

                if ($record->variants->count() <= $column->getListLimit()) {
                    return null;
                }

                if ($record->variants->count() > 30) {
                    $record->variants = $record->variants->slice(0, 30);
                }

                return $record->variants
                    ->map(fn ($variant) => $variant->sku)
                    ->implode(', ');
            })
            ->listWithLineBreaks()
            ->limitList(1)
            ->toggleable()
            ->searchable();
    }

    public static function getDefaultRelations(): array
    {
        return [
            RelationGroup::make('Availability', [
                ChannelRelationManager::class,
                CustomerGroupRelationManager::class,
            ]),
            MediaRelationManager::class,
            PriceRelationManager::class,
            CustomerGroupPricingRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => ProductResource\Pages\ListProducts::route('/'),
            'edit' => ProductResource\Pages\EditProduct::route('/{record}/edit'),
            'availability' => ProductResource\Pages\ManageProductAvailability::route('/{record}/availability'),
            'identifiers' => ProductResource\Pages\ManageProductIdentifiers::route('/{record}/identifiers'),
            'media' => ProductResource\Pages\ManageProductMedia::route('/{record}/media'),
            'pricing' => ProductResource\Pages\ManageProductPricing::route('/{record}/pricing'),
            'inventory' => ProductResource\Pages\ManageProductInventory::route('/{record}/inventory'),
            'shipping' => ProductResource\Pages\ManageProductShipping::route('/{record}/shipping'),
            'variants' => ProductResource\Pages\ManageProductVariants::route('/{record}/variants'),
            'urls' => ProductResource\Pages\ManageProductUrls::route('/{record}/urls'),
            'collections' => ProductResource\Pages\ManageProductCollections::route('/{record}/collections'),
            'associations' => ProductResource\Pages\ManageProductAssociations::route('/{record}/associations'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->translateAttribute('name');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'variants.sku',
            'tags.value',
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with([
            'variants',
            'brand',
            'tags',
        ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $firstVariant = $record->variants->first();

        return [
            __('admin::product.table.sku.label') => $firstVariant?->getIdentifier(),
            __('admin::product.table.stock.label') => $firstVariant?->stock,
            __('admin::product.table.brand.label') => $record->brand?->name,
        ];
    }
}
