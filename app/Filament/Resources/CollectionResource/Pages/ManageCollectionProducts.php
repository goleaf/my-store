<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Events\CollectionProductAttached;
use App\Events\CollectionProductDetached;
use App\Filament\Resources\CollectionResource;
use App\Filament\Resources\ProductResource;
use App\Models\Contracts\Product as ProductContract;
use App\Models\Product;
use App\Support\Pages\BaseManageRelatedRecords;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Actions;

class ManageCollectionProducts extends BaseManageRelatedRecords
{
    protected static string $resource = CollectionResource::class;

    protected static string $relationship = 'products';

    public ?string $tableSortColumn = 'position';

    public function getTitle(): string
    {
        return __('admin::collection.pages.products.label');
    }

    public function getBreadcrumbs(): array
    {
        $crumbs = static::getResource()::getCollectionBreadcrumbs($this->getRecord());

        $crumbs[] = $this->getBreadcrumb();

        return $crumbs;
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::products');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::collection.pages.products.label');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            Tables\Columns\TextColumn::make('foo'),
        ]);
    }

    public function reorderTable(array $order, string|int|null $draggedRecordKey = null): void
    {
        parent::reorderTable($order, $draggedRecordKey);

        foreach (Product::whereIn('id', $order)->get() as $product) {
            $product->searchable();
        }
    }

    public function table(Table $table): Table
    {
        return $table->columns([

            Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                ->collection(config('store.media.collection'))
                ->conversion('small')
                ->limit(1)
                ->square()
                ->label(''),
            Tables\Columns\TextColumn::make('attribute_data.name')
                ->formatStateUsing(fn (Model $record): string => $record->translateAttribute('name'))
                ->label(__('admin::product.table.name.label')),
        ])->actions([
            Actions\DetachAction::make()->after(
                fn () => CollectionProductDetached::dispatch($this->getOwnerRecord())
            ),
            Actions\EditAction::make()->url(
                fn (Model $record) => ProductResource::getUrl('edit', [
                    'record' => $record,
                ])
            ),
        ])->headerActions([
            Actions\AttachAction::make()
                ->label(
                    __('admin::collection.pages.products.actions.attach.label')
                )->form([
                    Forms\Components\Select::make('recordId')
                        ->label('Product')
                        ->required()
                        ->searchable(true)
                        ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search, ManageCollectionProducts $livewire): array {
                            $relationModel = $livewire->getRelationship()->getRelated()::class;

                            return get_search_builder($relationModel, $search)
                                ->get()
                                ->reject(
                                    fn (ProductContract $record) => $livewire->getRelationship()->get()->contains($record->getKey())
                                )
                                ->mapWithKeys(fn (ProductContract $record): array => [$record->getKey() => $record->translateAttribute('name')])
                                ->all();
                        }),
                ])->action(function (array $arguments, array $data, Form $form, Table $table) {
                    $relationship = Relation::noConstraints(fn () => $table->getRelationship());

                    $product = Product::find($data['recordId']);

                    $relationship->attach($product, [
                        'position' => $relationship->count() + 1,
                    ]);

                    CollectionProductAttached::dispatch($this->getOwnerRecord());

                    $product->searchable();
                }),
        ])->reorderable('position');
    }
}
