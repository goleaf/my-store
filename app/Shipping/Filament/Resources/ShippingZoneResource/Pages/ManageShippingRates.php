<?php

namespace App\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use App\Filament\Components\Shout;
use App\Shipping\Filament\Resources\ShippingZoneResource;
use App\Shipping\Enums\ShippingMethodChargeBy;
use App\Shipping\Models\ShippingMethod;
use App\Shipping\Models\ShippingRate;
use App\Models\Currency;
use App\Models\CustomerGroup;
use App\Models\Price;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use App\Shipping\Models\Contracts;

class ManageShippingRates extends ManageRelatedRecords
{
    protected static string $resource = ShippingZoneResource::class;

    protected static string $relationship = 'rates';

    public function getTitle(): string|Htmlable
    {
        return __('storepanel.shipping::relationmanagers.shipping_rates.title_plural');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::shipping-rates');
    }

    public static function getNavigationLabel(): string
    {
        return __('storepanel.shipping::relationmanagers.shipping_rates.title_plural');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Shout::make('shipping-rate-pricing-notice')->content(
                function () {
                    $pricesIncTax = config('store.pricing.stored_inclusive_of_tax', false);

                    if ($pricesIncTax) {
                        return __('storepanel.shipping::relationmanagers.shipping_rates.notices.prices_inc_tax');
                    }

                    return __('storepanel.shipping::relationmanagers.shipping_rates.notices.prices_excl_tax');
                }
            ),
            Forms\Components\Select::make('shipping_method_id')
                ->label(
                    __('storepanel.shipping::relationmanagers.shipping_rates.form.shipping_method_id.label')
                )
                ->required()
                ->live()
                ->relationship(name: 'shippingMethod', titleAttribute: 'name')
                ->columnSpan(2),
            Forms\Components\TextInput::make('price')
                ->label(
                    __('storepanel.shipping::relationmanagers.shipping_rates.form.price.label')
                )
                ->numeric()
                ->required()
                ->columnSpan(2)
                ->afterStateHydrated(static function (Forms\Components\TextInput $component, ?Model $record = null): void {
                    if ($record) {
                        $basePrice = $record->basePrices->first();

                        $component->state(
                            $basePrice->price->decimal
                        );
                    }
                }),
            Forms\Components\Repeater::make('prices')
                ->label(
                    __('storepanel.shipping::relationmanagers.shipping_rates.form.prices.label')
                )->schema([
                    Forms\Components\Select::make('customer_group_id')
                        ->label(
                            __('storepanel.shipping::relationmanagers.shipping_rates.form.prices.repeater.customer_group_id.label')
                        )
                        ->options(
                            fn () => CustomerGroup::query()->orderBy('name')->pluck('name', 'id')
                        )->placeholder(
                            __('storepanel.shipping::relationmanagers.shipping_rates.form.prices.repeater.customer_group_id.placeholder')
                        )->preload(),
                    Forms\Components\Select::make('currency_id')
                        ->label(
                            __('storepanel.shipping::relationmanagers.shipping_rates.form.prices.repeater.currency_id.label')
                        )
                        ->options(
                            fn () => Currency::query()
                                ->select(['id', 'name', 'default'])
                                ->orderBy('default', 'desc')
                                ->orderBy('name')
                                ->pluck('name', 'id')
                        )->default(
                            Currency::getDefault()?->id
                        )->required()->preload(),
                    Forms\Components\TextInput::make('price')
                        ->label(
                            __('storepanel.shipping::relationmanagers.shipping_rates.form.prices.repeater.price.label')
                        )
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('min_quantity')
                        ->label(
                            function (Get $get) {
                                if (static::getShippingChargeBy($get('../../shipping_method_id')) === ShippingMethodChargeBy::Weight) {
                                    return __('storepanel.shipping::relationmanagers.shipping_rates.form.prices.repeater.min_weight.label');
                                }

                                return __('storepanel.shipping::relationmanagers.shipping_rates.form.prices.repeater.min_spend.label');
                            }
                        )
                        ->numeric()
                        ->required(),
                ])->afterStateHydrated(
                    static function (Forms\Components\Repeater $component, ?Model $record = null): void {
                        if ($record) {
                            $chargeBy = static::getShippingChargeBy($record->shippingMethod);
                            $currencies = Currency::query()
                                ->select(['id', 'factor'])
                                ->get()
                                ->keyBy('id');

                            $component->state(
                                $record->priceBreaks->map(function ($price) use ($chargeBy, $currencies) {
                                $currency = $currencies->get($price->currency_id);

                                    return [
                                        'customer_group_id' => $price->customer_group_id,
                                        'price' => $price->price->decimal,
                                        'currency_id' => $price->currency_id,
                                        'min_quantity' => $chargeBy === ShippingMethodChargeBy::CartTotal ? $price->min_quantity / $currency->factor : $price->min_quantity / 100,
                                    ];
                                })->toArray()
                            );
                        }
                    }
                )->columns(4),
        ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('shippingMethod.name')
                ->label(__('storepanel.shipping::relationmanagers.shipping_rates.table.shipping_method.label'))
                ->formatStateUsing(fn ($state, Model $record) => $state.(! $record->enabled ? ' • '.__('storepanel.shipping::relationmanagers.shipping_rates.table.shipping_method.disabled') : '')),
            TextColumn::make('basePrices.0')->formatStateUsing(
                fn ($state = null) => $state->price->formatted
            )->label(
                __('storepanel.shipping::relationmanagers.shipping_rates.table.price.label')
            ),
            TextColumn::make('price_breaks_count')
                ->label(
                    __('storepanel.shipping::relationmanagers.shipping_rates.table.price_breaks_count.label')
                )->counts('priceBreaks'),
        ])->headerActions([
            Actions\CreateAction::make()->label(
                __('storepanel.shipping::relationmanagers.shipping_rates.actions.create.label')
            )->action(function (Table $table, ?ShippingRate $shippingRate = null, array $data = []) {
                $relationship = $table->getRelationship();

                $record = new ShippingRate;
                $record->shipping_method_id = $data['shipping_method_id'];
                $relationship->save($record);

                static::saveShippingRate($record, $data);
            })->slideOver(),
        ])->actions([

            Actions\EditAction::make()->slideOver()->action(function (ShippingRate $shippingRate, array $data) {
                static::saveShippingRate($shippingRate, $data);
            }),
            Actions\DeleteAction::make()->requiresConfirmation(),
            Actions\Action::make('disable')->color('warning')->action(function (ShippingRate $shippingRate) {
                $shippingRate->updateQuietly([
                    'enabled' => false,
                ]);
            })->hidden(
                fn (ShippingRate $shippingRate) => ! $shippingRate->enabled
            ),
            Actions\Action::make('enable')->color('success')->action(function (ShippingRate $shippingRate) {
                $shippingRate->updateQuietly([
                    'enabled' => true,
                ]);
            })->hidden(
                fn (ShippingRate $shippingRate) => (bool) $shippingRate->enabled
            ),

        ]);
    }

    private static function getShippingChargeBy(Contracts\ShippingMethod|int|null $method): ShippingMethodChargeBy
    {
        if (blank($method)) {
            return ShippingMethodChargeBy::CartTotal;
        }

        if (! $method instanceof Contracts\ShippingMethod) {
            $method = ShippingMethod::find($method);
        }

        return ShippingMethodChargeBy::resolve(($method?->data['charge_by'] ?? null)) ?? ShippingMethodChargeBy::CartTotal;
    }

    protected static function saveShippingRate(?ShippingRate $shippingRate = null, array $data = []): void
    {
        $currency = Currency::getDefault();

        $basePrice = $shippingRate->basePrices->first() ?: new Price;

        $basePrice->price = (int) ($data['price'] * $currency->factor);
        $basePrice->priceable_type = $shippingRate->getMorphClass();
        $basePrice->currency_id = $currency->id;
        $basePrice->priceable_id = $shippingRate->id;
        $basePrice->customer_group_id = null;
        $basePrice->save();

        $shippingRate->priceBreaks()->delete();

        $currencies = Currency::query()
            ->select(['id', 'factor'])
            ->get()
            ->keyBy('id');
        $chargeBy = static::getShippingChargeBy($shippingRate->shippingMethod);

        $tiers = collect($data['prices'] ?? [])->map(
            function ($price) use ($chargeBy, $currencies) {
                $currency = $currencies->get($price['currency_id']);

                if (! $currency) {
                    return null;
                }

                if ($chargeBy === ShippingMethodChargeBy::CartTotal) {
                    $price['min_quantity'] = (int) ($price['min_quantity'] * $currency->factor);
                } else {
                    $price['min_quantity'] = (int) ($price['min_quantity'] * 100);
                }

                $price['price'] = (int) ($price['price'] * $currency->factor);

                return $price;
            }
        )->filter();

        $shippingRate->prices()->createMany($tiers->toArray());
    }
}
