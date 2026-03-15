<?php

namespace App\Support\RelationManagers;

use App\Events\ModelPricesUpdated;
use App\Store\Facades\DB;
use App\Store\Models\Currency;
use App\Store\Models\CustomerGroup;
use App\Store\Models\Price;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class PriceRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'prices';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::relationmanagers.pricing.tab_name');
    }

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('admin::relationmanagers.pricing.table.heading');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\Group::make([
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
                        )->placeholder(
                            __('admin::relationmanagers.pricing.form.customer_group_id.placeholder')
                        )->helperText(
                            __('admin::relationmanagers.pricing.form.customer_group_id.helper_text')
                        )->relationship(name: 'customerGroup', titleAttribute: 'name'),
                    Forms\Components\TextInput::make('min_quantity')
                        ->label(
                            __('admin::relationmanagers.pricing.form.min_quantity.label')
                        )->helperText(
                            __('admin::relationmanagers.pricing.form.min_quantity.helper_text')
                        )->numeric()
                        ->default(2)
                        ->minValue(2)
                        ->required()
                        ->rules([
                            fn (Forms\Get $get, $record) => function (string $attribute, $value, Closure $fail) use ($get, $form, $record) {
                                $owner = $this->getOwnerRecord();

                                $price = $form->getModel();

                                $exist = $price::query()
                                    ->when(filled($record), fn ($query) => $query->where('id', '!=', $record->id))
                                    ->when(blank($get('customer_group_id')),
                                        fn ($query) => $query->whereNull('customer_group_id'),
                                        fn ($query) => $query->where('customer_group_id', $get('customer_group_id')))
                                    ->where('currency_id', $get('currency_id'))
                                    ->where('priceable_type', $owner->getMorphClass())
                                    ->where('priceable_id', $owner->id)
                                    ->where('min_quantity', $get('min_quantity'))
                                    ->count();

                                if ($exist) {
                                    $fail(__('admin::relationmanagers.pricing.form.min_quantity.validation.unique'));
                                }
                            },
                        ]),
                ])->columns(3),

                Forms\Components\Group::make([
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

    public function table(Table $table): Table
    {
        $priceTable = (new Price)->getTable();
        $cgTable = CustomerGroup::query()->select([DB::raw('id as cg_id'), 'name']);

        return $table
            ->recordTitleAttribute('name')
            ->description(
                __('admin::relationmanagers.pricing.table.description')
            )
            ->modifyQueryUsing(
                fn ($query) => $query
                    ->leftJoinSub($cgTable, 'cg', fn ($join) => $join->on('customer_group_id', 'cg.cg_id'))
                    ->where("{$priceTable}.min_quantity", '>', 1)
            )
            ->defaultSort(fn ($query) => $query->orderBy('cg.name')->orderBy('min_quantity'))
            ->emptyStateHeading(
                __('admin::relationmanagers.pricing.table.empty_state.label')
            )
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->label(
                        __('admin::relationmanagers.pricing.table.price.label')
                    )->formatStateUsing(
                        fn ($state) => $state?->formatted,
                    )->sortable(),
                Tables\Columns\TextColumn::make('currency.code')->label(
                    __('admin::relationmanagers.pricing.table.currency.label')
                )->sortable(),
                Tables\Columns\TextColumn::make('min_quantity')->label(
                    __('admin::relationmanagers.pricing.table.min_quantity.label')
                )->sortable(),
                Tables\Columns\TextColumn::make('customerGroup.name')->label(
                    __('admin::relationmanagers.pricing.table.customer_group.label')
                )->placeholder(
                    __('admin::relationmanagers.pricing.table.customer_group.placeholder')
                )->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->relationship(name: 'currency', titleAttribute: 'name')
                    ->preload()
                    ->label(
                        __('admin::relationmanagers.pricing.table.currency.label')
                    ),
                Tables\Filters\SelectFilter::make('min_quantity')->options(
                    Price::where('priceable_id', $this->getOwnerRecord()->id)
                        ->where('priceable_type', $this->getOwnerRecord()->getMorphClass())
                        ->get()
                        ->pluck('min_quantity', 'min_quantity')
                )->label(
                    __('admin::relationmanagers.pricing.table.min_quantity.label')
                ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function (array $data) {
                    $currencyModel = Currency::find($data['currency_id'] ?? null);

                    if ($currencyModel) {
                        $data['price'] = (int) ($data['price'] * $currencyModel->factor);
                    }

                    return $data;
                })->label(
                    __('admin::relationmanagers.pricing.table.actions.create.label')
                )->after(
                    fn () => ModelPricesUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $currencyModel = Currency::find($data['currency_id'] ?? null);

                    if ($currencyModel) {
                        $data['price'] = (int) ($data['price'] * $currencyModel->factor);
                    }

                    return $data;
                })->after(
                    fn () => ModelPricesUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
                Tables\Actions\DeleteAction::make()->after(
                    fn () => ModelPricesUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ]);
    }
}
