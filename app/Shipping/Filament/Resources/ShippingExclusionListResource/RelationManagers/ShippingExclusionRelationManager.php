<?php

namespace App\Shipping\Filament\Resources\ShippingExclusionListResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contracts\Product as ProductContract;
use App\Models\Product;
use Filament\Actions;

class ShippingExclusionRelationManager extends RelationManager
{
    protected static string $relationship = 'exclusions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('storepanel.shipping::relationmanagers.exclusions.title_plural');
    }

    protected static ?string $recordTitleAttribute = 'name';

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\MorphToSelect::make('purchasable')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(Product::modelClass())
                            ->titleAttribute('name')
                            ->getOptionLabelUsing(
                                fn (Model $record) => $record->purchasable->attr('name')
                            )
                            ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                                return get_search_builder(Product::modelClass(), $search)
                                    ->get()
                                    ->mapWithKeys(fn (ProductContract $record): array => [$record->getKey() => $record->translateAttribute('name')])
                                    ->all();
                            }),
                    ])
                    ->label(
                        __('storepanel.shipping::relationmanagers.exclusions.form.purchasable.label')
                    )
                    ->required()
                    ->searchable(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('purchasable.thumbnail')
                    ->collection(config('store.media.collection'))
                    ->conversion('small')
                    ->limit(1)
                    ->square()
                    ->label(''),
                Tables\Columns\TextColumn::make('purchasable')
                    ->formatStateUsing(
                        fn ($state) => $state->attr('name')
                    )
                    ->limit(50)
                    ->label(__('admin::product.table.name.label')),
                Tables\Columns\TextColumn::make('purchasable.variants.sku')
                    ->label(__('admin::product.table.sku.label'))
                    ->tooltip(function (Tables\Columns\TextColumn $column, $state): ?string {

                        $skus = collect($state);

                        if ($skus->count() <= $column->getListLimit()) {
                            return null;
                        }

                        if ($skus->count() > 30) {
                            $skus = $skus->slice(0, 30);
                        }

                        return $skus->implode(', ');
                    })
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()->mutateFormDataUsing(function (array $data, RelationManager $livewire) {
                    return $data;
                }),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
