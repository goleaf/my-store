<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Events\ProductAssociationsUpdated;
use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductAssociation;
use App\Support\Pages\BaseManageRelatedRecords;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use App\Base\Enums;
use App\Models\Contracts;

class ManageProductAssociations extends BaseManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'associations';

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-associations');
    }

    public function getTitle(): string
    {
        return __('admin::product.pages.associations.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.associations.label');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('product_target_id')
                    ->label('Product')
                    ->required()
                    ->searchable(true)
                    ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                        return get_search_builder(Product::modelClass(), $search)
                            ->get()
                            ->mapWithKeys(fn (Contracts\Product $record): array => [$record->getKey() => $record->translateAttribute('name')])
                            ->all();
                    }),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(ProductAssociation::getTypes()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->inverseRelationship('parent')
            ->columns([
                Tables\Columns\TextColumn::make('target')
                    ->formatStateUsing(fn (Contracts\ProductAssociation $record): string => $record->target->translateAttribute('name'))
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column, Contracts\ProductAssociation $record): ?string {
                        $state = $column->getState();

                        if (strlen($record->target->translateAttribute('name')) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $record->target->translateAttribute('name');
                    })
                    ->label(__('admin::product.table.name.label')),
                Tables\Columns\TextColumn::make('target.variants.sku')
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('type')->formatStateUsing(function ($state) {
                    $enum = config('store.products.association_types_enum', Enums\ProductAssociation::class);

                    return $enum::tryFrom($state)?->label() ?: $state;
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()->after(
                    fn () => ProductAssociationsUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ])
            ->actions([
                Actions\DeleteAction::make()->after(
                    fn () => ProductAssociationsUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()->after(
                        fn () => ProductAssociationsUpdated::dispatch(
                            $this->getOwnerRecord()
                        )
                    ),
                ]),
            ]);
    }
}
