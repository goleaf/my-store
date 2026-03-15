<?php

namespace App\Admin\Filament\Resources\DiscountResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Admin\Support\RelationManagers\BaseRelationManager;
use App\Store\Models\Contracts\Product as ProductContract;
use App\Store\Models\Product;

class ProductLimitationRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'discountables';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table
            ->heading(
                __('admin::discount.relationmanagers.products.title')
            )
            ->description(
                __('admin::discount.relationmanagers.products.description')
            )
            ->paginated(false)
            ->modifyQueryUsing(
                fn ($query) => $query->whereIn('type', ['limitation', 'exclusion'])
                    ->whereDiscountableType(Product::morphName())
                    ->whereHas('discountable')
            )
            ->headerActions([
                Tables\Actions\CreateAction::make()->form([
                    Forms\Components\MorphToSelect::make('discountable')
                        ->searchable(true)
                        ->types([
                            Forms\Components\MorphToSelect\Type::make(Product::modelClass())
                                ->titleAttribute('name.en')
                                ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                                    return get_search_builder(Product::modelClass(), $search)
                                        ->get()
                                        ->mapWithKeys(fn (ProductContract $record): array => [$record->getKey() => $record->attr('name')])
                                        ->all();
                                }),
                        ]),
                ])->label(
                    __('admin::discount.relationmanagers.products.actions.attach.label')
                )->mutateFormDataUsing(function (array $data) {
                    $data['type'] = 'limitation';

                    return $data;
                }),
            ])->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('discountable.thumbnail')
                    ->collection(config('store.media.collection'))
                    ->conversion('small')
                    ->limit(1)
                    ->square()
                    ->label(''),
                Tables\Columns\TextColumn::make('discountable.attribute_data.name')
                    ->label(
                        __('admin::discount.relationmanagers.products.table.name.label')
                    )
                    ->formatStateUsing(
                        fn (Model $record) => $record->discountable->attr('name')
                    ),
            ])->actions([
                Tables\Actions\DeleteAction::make(),
            ])->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
