<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\RelationManagers\CustomerGroupPricingRelationManager;
use App\Filament\Resources\ProductVariantResource;
use App\Models\Currency;
use App\Models\Price;
use App\Support\Concerns\Products\ManagesProductPricing;
use App\Support\Pages\BaseEditRecord;
use App\Support\RelationManagers\PriceRelationManager;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class ManageProductPricing extends BaseEditRecord
{
    use ManagesProductPricing;

    protected static string $resource = ProductResource::class;

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-pricing');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->variants()->withTrashed()->count() == 1;
    }

    public function getOwnerRecord(): Model
    {
        return $this->getRecord()->variants()->withTrashed()->first();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        if (! count($this->basePrices)) {
            $this->basePrices = $this->getBasePrices();
        }

        $schema->components([
            SchemaComponents\Section::make()
                ->schema([
                    SchemaComponents\Group::make([
                        ProductVariantResource::getTaxClassIdFormComponent(),
                        ProductVariantResource::getTaxRefFormComponent(),
                    ])->columns(2),
                ]),
            $this->getBasePriceFormSection(),
        ])->statePath('');

        $this->callStoreHook('extendForm', $schema);

        return $schema;
    }

    public function getRelationManagers(): array
    {
        return [
            CustomerGroupPricingRelationManager::make([
                'ownerRecord' => $this->getOwnerRecord(),
            ]),
            PriceRelationManager::make([
                'ownerRecord' => $this->getOwnerRecord(),
            ]),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::relationmanagers.pricing.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(
                fn ($query) => $query->orderBy('min_quantity', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->label(
                        __('admin::relationmanagers.pricing.table.price.label')
                    )->formatStateUsing(
                        fn ($state) => $state?->formatted,
                    ),
                Tables\Columns\TextColumn::make('currency.code')->label(
                    __('admin::relationmanagers.pricing.table.currency.label')
                ),
                Tables\Columns\TextColumn::make('min_quantity')->label(
                    __('admin::relationmanagers.pricing.table.min_quantity.label')
                ),
                Tables\Columns\TextColumn::make('customerGroup.name')->label(
                    __('admin::relationmanagers.pricing.table.customer_group.label')
                ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->relationship(name: 'currency', titleAttribute: 'name')
                    ->preload(),
                Tables\Filters\SelectFilter::make('min_quantity')->options(
                    Price::where('priceable_id', $this->getOwnerRecord()->id)
                        ->where('priceable_type', $this->getOwnerRecord()->getMorphClass())
                        ->get()
                        ->pluck('min_quantity', 'min_quantity')
                ),
            ])
            ->headerActions([
                Actions\CreateAction::make()->mutateFormDataUsing(function (array $data) {
                    $currencyModel = Currency::find($data['currency_id'] ?? null);

                    if ($currencyModel) {
                        $data['price'] = (int) ($data['price'] * $currencyModel->factor);
                    }

                    return $data;
                }),
            ])
            ->actions([
                Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $currencyModel = Currency::find($data['currency_id'] ?? null);

                    if ($currencyModel) {
                        $data['price'] = (int) ($data['price'] * $currencyModel->factor);
                    }

                    return $data;
                }),
                Actions\DeleteAction::make(),
            ]);
    }
}
