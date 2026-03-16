<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Events\ProductPricingUpdated;
use App\Facades\DB;
use App\Models\Currency;
use App\Models\CustomerGroup;
use App\Models\Price;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class CustomerGroupPricingRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'prices';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::relationmanagers.customer_group_pricing.title');
    }

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('admin::relationmanagers.customer_group_pricing.table.heading');
    }

    public function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                SchemaComponents\Group::make([
                    Forms\Components\Select::make('currency_id')
                        ->label(
                            __('admin::relationmanagers.pricing.form.currency_id.label')
                        )->relationship(name: 'currency', titleAttribute: 'name')
                        ->default(function () {
                            return Currency::getDefault()?->id;
                        })
                        ->helperText(
                            __('admin::relationmanagers.pricing.form.currency_id.helper_text')
                        )->required(),
                    Forms\Components\Select::make('customer_group_id')
                        ->label(
                            __('admin::relationmanagers.pricing.form.customer_group_id.label')
                        )->helperText(
                            __('admin::relationmanagers.pricing.form.customer_group_id.helper_text')
                        )->relationship(name: 'customerGroup', titleAttribute: 'name')
                        ->required()
                        ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, Forms\Get $get) {
                            $owner = $this->getOwnerRecord();

                            return $rule
                                ->when(blank($get('customer_group_id')),
                                    fn (Unique $rule) => $rule->whereNull('customer_group_id'),
                                    fn (Unique $rule) => $rule->where('customer_group_id', $get('customer_group_id')))
                                ->where('min_quantity', 1)
                                ->where('currency_id', $get('currency_id'))
                                ->where('priceable_type', $owner->getMorphClass())
                                ->where('priceable_id', $owner->id);
                        }),
                ])->columns(2),

                SchemaComponents\Group::make([
                    Forms\Components\TextInput::make('price')->formatStateUsing(
                        fn ($state) => $state?->decimal(rounding: false)
                    )->numeric()->helperText(
                        __('admin::relationmanagers.pricing.form.price.helper_text')
                    )->required(),
                    Forms\Components\TextInput::make('compare_price')->formatStateUsing(
                        fn ($state) => $state?->decimal(rounding: false)
                    )->label(
                        __('admin::relationmanagers.pricing.form.compare_price.label')
                    )->helperText(
                        __('admin::relationmanagers.pricing.form.compare_price.helper_text')
                    )->numeric(),
                ])->columns(2),
            ])->columns(1);
    }

    public function getDefaultTable(Table $table): Table
    {
        $priceTable = (new Price)->getTable();
        $cgTable = CustomerGroup::query()->select([DB::raw('id as cg_id'), 'name']);

        return $table
            ->recordTitleAttribute('name')
            ->description(
                __('admin::relationmanagers.customer_group_pricing.table.description')
            )
            ->modifyQueryUsing(
                fn ($query) => $query
                    ->leftJoinSub($cgTable, 'cg', fn ($join) => $join->on('customer_group_id', 'cg.cg_id'))
                    ->where("{$priceTable}.min_quantity", 1)
                    ->whereNotNull("{$priceTable}.customer_group_id")
            )
            ->defaultSort(fn ($query) => $query->orderBy('cg.name')->orderBy('min_quantity'))
            ->emptyStateHeading(
                __('admin::relationmanagers.customer_group_pricing.table.empty_state.label')
            )
            ->emptyStateDescription(__('admin::relationmanagers.customer_group_pricing.table.empty_state.description'))
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->label(
                        __('admin::relationmanagers.pricing.table.price.label')
                    )->formatStateUsing(
                        fn ($state) => $state->formatted,
                    )->sortable(),
                Tables\Columns\TextColumn::make('currency.code')->label(
                    __('admin::relationmanagers.pricing.table.currency.label')
                )->sortable(),
                Tables\Columns\TextColumn::make('customerGroup.name')->label(
                    __('admin::relationmanagers.pricing.table.customer_group.label')
                )->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->relationship(name: 'currency', titleAttribute: 'name')
                    ->preload()
                    ->label(
                        __('admin::relationmanagers.pricing.table.currency.label')
                    ),
            ])
            ->headerActions([
                Actions\CreateAction::make()->mutateFormDataUsing(function (array $data) {
                    $currencyModel = Currency::find($data['currency_id'] ?? null);

                    $data['min_quantity'] = 1;
                    if ($currencyModel) {
                        $data['price'] = (int) ($data['price'] * $currencyModel->factor);
                        $data['compare_price'] = (int) (($data['compare_price'] ?? 0) * $currencyModel->factor);
                    }

                    return $data;
                })->label(
                    __('admin::relationmanagers.customer_group_pricing.table.actions.create.label')
                )->modalHeading(__('admin::relationmanagers.customer_group_pricing.table.actions.create.modal.heading'))
                    ->after(
                        fn () => ProductPricingUpdated::dispatch($this->getOwnerRecord())
                    ),
            ])
            ->actions([
                Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $currencyModel = Currency::find($data['currency_id'] ?? null);

                    $data['min_quantity'] = 1;
                    if ($currencyModel) {
                        $data['price'] = (int) ($data['price'] * $currencyModel->factor);
                        $data['compare_price'] = (int) (($data['compare_price'] ?? 0) * $currencyModel->factor);
                    }

                    return $data;
                })->after(
                    fn () => ProductPricingUpdated::dispatch($this->getOwnerRecord())
                ),
                Actions\DeleteAction::make(),
            ]);
    }
}
