<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Contracts\Brand;
use App\Support\Forms\Components\Attributes;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Schemas\Components;

class BrandResource extends BaseResource
{
    protected static ?string $permission = 'catalog:manage-products';

    protected static ?string $model = Brand::class;

    protected static ?int $navigationSort = 3;

    protected static int $globalSearchResultsLimit = 5;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('admin::brand.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::brand.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::brands');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.catalog');
    }

    public static function getDefaultSubNavigation(): array
    {
        return [
            BrandResource\Pages\EditBrand::class,
            BrandResource\Pages\ManageBrandMedia::class,
            BrandResource\Pages\ManageBrandUrls::class,
            BrandResource\Pages\ManageBrandProducts::class,
            BrandResource\Pages\ManageBrandCollections::class,
        ];
    }

    public static function getDefaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make()
                    ->schema(
                        static::getMainFormComponents(),
                    ),
                static::getAttributeDataFormComponent(),
            ])
            ->columns(1);
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
            ->label(__('admin::brand.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getAttributeDataFormComponent(): Component
    {
        return Attributes::make();
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
            ])->searchable();
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\ImageColumn::make('thumbnail_image')
                ->state(fn (Model $record): string => $record->thumbnail?->getUrl('small') ?? '')
                ->square()
                ->label(''),
            Tables\Columns\TextColumn::make('name')
                ->label(__('admin::brand.table.name.label'))
                ->searchable(),
            Tables\Columns\TextColumn::make('products_count')
                ->counts('products')
                ->formatStateUsing(
                    fn ($state) => number_format($state, 0)
                )
                ->label(__('admin::brand.table.products_count.label')),
        ];
    }

    public static function getDefaultRelations(): array
    {
        return [

        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => BrandResource\Pages\ListBrands::route('/'),
            'create' => BrandResource\Pages\CreateBrand::route('/create'),
            'edit' => BrandResource\Pages\EditBrand::route('/{record}/edit'),
            'media' => BrandResource\Pages\ManageBrandMedia::route('/{record}/media'),
            'urls' => BrandResource\Pages\ManageBrandUrls::route('/{record}/urls'),
            'products' => BrandResource\Pages\ManageBrandProducts::route('/{record}/products'),
            'collections' => BrandResource\Pages\ManageBrandCollections::route('/{record}/collections'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('thumbnail');
    }
}
