<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Collection;
use App\Filament\Resources\TaxZoneResource\Pages;
use App\Filament\Clusters\Taxes;
use App\Models\Contracts\TaxZone;
use App\Models\Country;
use App\Models\State;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Schemas\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Schemas\Components;

class TaxZoneResource extends BaseResource
{
    protected static ?string $cluster = Taxes::class;

    protected static ?string $permission = 'settings:core';

    protected static ?string $model = TaxZone::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::taxzone.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::taxzone.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::tax');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            Components\Section::make()->schema([
                static::getNameFormComponent(),
                static::getPriceDisplayFormComponent(),
                Components\Group::make([
                    static::getActiveFormComponent(),
                    static::getDefaultFormComponent(),
                ])->columns(2),
                static::getZoneTypeFormComponent(),
                static::getZoneTypeCountriesFormComponent(),
                static::getZoneTypeCountryFormComponent(),
                static::getZoneTypeStatesFormComponent(),
                static::getZoneTypePostcodesFormComponent(),
            ]),
        ];
    }

    public static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin::taxzone.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    public static function getZoneTypeFormComponent(): Component
    {
        return Forms\Components\Select::make('zone_type')
            ->options([
                'country' => __('admin::taxzone.form.zone_type.options.country'),
                'states' => __('admin::taxzone.form.zone_type.options.states'),
                'postcodes' => __('admin::taxzone.form.zone_type.options.postcodes'),
            ])
            ->default('country')
            ->label(__('admin::taxzone.form.zone_type.label'))
            ->live()
            ->required()
            ->selectablePlaceholder(false);
    }

    protected static function getZoneTypeCountriesFormComponent(): Component
    {
        return Forms\Components\Select::make('zone_countries')
            ->label(__('admin::taxzone.form.zone_countries.label'))
            ->visible(fn ($get) => $get('zone_type') == 'country')
            ->dehydrated(false)
            ->options(Country::get()->pluck('name', 'iso3'))
            ->multiple()
            ->required()
            ->loadStateFromRelationshipsUsing(static function (Forms\Components\Select $component, Model $record): void {
                $record->loadMissing('countries.country');

                /** @var Collection $relatedModels */
                $relatedModels = $record->countries;

                $component->state(
                    $relatedModels
                        ->pluck('country.iso3')
                        ->map(static fn ($key): string => strval($key))
                        ->toArray(),
                );
            })->getOptionLabelsUsing(static function (Model $record): array {
                $record->loadMissing('countries.country');

                return $record->countries
                    ->pluck('country.name', 'country.iso3')
                    ->toArray();
            })
            ->saveRelationshipsUsing(static function (Model $record, $state) {
                $selectedCountries = Country::whereIn('iso3', $state)->get()->pluck('id');

                self::syncCountries($record, $selectedCountries);

                $record->states()->delete();
                $record->postcodes()->delete();
            });
    }

    protected static function getZoneTypeCountryFormComponent(): Component
    {
        return Forms\Components\Select::make('zone_country')
            ->label(__('admin::taxzone.form.zone_country.label'))
            ->visible(fn ($get) => $get('zone_type') !== 'country')
            ->dehydrated(false)
            ->required()
            ->options(Country::get()->pluck('name', 'id'))
            ->searchable()
            ->afterStateHydrated(static function (Forms\Components\Select $component, ?Model $record): void {
                if ($record) {
                    $record->loadMissing('countries.country');

                    /** @var Collection $relatedModels */
                    $relatedModels = $record->countries;

                    $component->state(
                        $relatedModels
                            ->pluck('country')
                            ->first()?->id,
                    );
                }
            });
    }

    protected static function getZoneTypeStatesFormComponent(): Component
    {
        return Forms\Components\Select::make('zone_states')
            ->label(__('admin::taxzone.form.zone_states.label'))
            ->visible(fn ($get) => $get('zone_type') == 'states')
            ->dehydrated(false)
            ->options(fn ($get) => State::where('country_id', $get('zone_country'))->get()->pluck('name', 'code'))
            ->multiple()
            ->required()
            ->loadStateFromRelationshipsUsing(static function (Forms\Components\Select $component, Model $record): void {
                $record->loadMissing('states.state');

                /** @var Collection $relatedModels */
                $relatedModels = $record->states;

                $component->state(
                    $relatedModels
                        ->pluck('state.code')
                        ->map(static fn ($key): string => strval($key))
                        ->toArray(),
                );
            })->getOptionLabelsUsing(static function (Model $record): array {
                $record->loadMissing('states.state');

                return $record->states
                    ->pluck('state.name', 'state.code')
                    ->toArray();
            })
            ->saveRelationshipsUsing(static function (Model $record, $state, $get) {
                $selectedStates = State::where('country_id', $get('zone_country'))->whereIn('code', $state)->get()->pluck('id');

                self::syncCountries($record, [$get('zone_country')]);
                self::syncStates($record, $selectedStates);

                $record->postcodes()->delete();
            });
    }

    protected static function getZoneTypePostcodesFormComponent(): Component
    {
        return Forms\Components\Textarea::make('zone_postcodes')
            ->label(__('admin::taxzone.form.zone_postcodes.label'))
            ->visible(fn ($get) => $get('zone_type') == 'postcodes')
            ->dehydrated(false)
            ->rows(10)
            ->helperText(__('admin::taxzone.form.zone_postcodes.helper'))
            ->required()
            ->afterStateHydrated(static function (Forms\Components\Textarea $component, ?Model $record): void {
                if ($record) {
                    /** @var Collection $relatedModels */
                    $relatedModels = $record->postcodes;

                    $component->state(
                        $relatedModels
                            ->pluck('postcode')
                            ->join("\n"),
                    );
                }
            })
            ->saveRelationshipsUsing(static function (Model $record, $state, $get) {
                self::syncCountries($record, [$get('zone_country')]);
                self::syncPostcodes($record, $get('zone_country'), $state);

                $record->states()->delete();
            });
    }

    private static function syncCountries(TaxZone $taxZone, $selectedCountries)
    {
        $existingCountries = $taxZone->countries()->pluck('country_id');

        $countriesToAssign = collect($selectedCountries)
            ->reject(function ($countryId) use ($existingCountries) {
                return $existingCountries->contains($countryId);
            });

        $taxZone->countries()->createMany(
            $countriesToAssign->map(fn ($countryId) => [
                'country_id' => $countryId,
            ])
        );

        $taxZone->countries()
            ->whereNotIn('country_id', $selectedCountries)
            ->delete();
    }

    private static function syncStates(TaxZone $taxZone, $selectedStates)
    {
        $existingStates = $taxZone->states()->pluck('state_id');

        $statesToAssign = collect($selectedStates)
            ->reject(function ($stateId) use ($existingStates) {
                return $existingStates->contains($stateId);
            });

        $taxZone->states()->createMany(
            $statesToAssign->map(fn ($stateId) => [
                'state_id' => $stateId,
            ])
        );

        $taxZone->states()
            ->whereNotIn('state_id', $selectedStates)
            ->delete();
    }

    private static function syncPostcodes(TaxZone $taxZone, $countryId, $postcodes)
    {
        $postcodes = collect(
            explode(
                "\n",
                str_replace(' ', '', $postcodes)
            )
        )->unique()->filter();

        $taxZone->postcodes()->delete();

        $taxZone->postcodes()->createMany(
            $postcodes->map(function ($postcode) use ($countryId) {
                return [
                    'country_id' => $countryId,
                    'postcode' => $postcode,
                ];
            })
        );
    }

    public static function getPriceDisplayFormComponent(): Component
    {
        return Forms\Components\Select::make('price_display')
            ->options([
                'tax_inclusive' => __('admin::taxzone.form.price_display.options.include_tax'),
                'tax_exclusive' => __('admin::taxzone.form.price_display.options.exclude_tax'),
            ])
            ->label(__('admin::taxzone.form.price_display.label'))
            ->required();
    }

    protected static function getActiveFormComponent(): Component
    {
        return Forms\Components\Toggle::make('active')
            ->label(__('admin::taxzone.form.active.label'));
    }

    protected static function getDefaultFormComponent(): Component
    {
        return Forms\Components\Toggle::make('default')
            ->label(__('admin::taxzone.form.default.label'));
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
            ]);
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('admin::taxzone.table.name.label'))
                ->formatStateUsing(fn ($state, Model $record) => $state.($record->default ? ' • '.__('admin::taxzone.table.default.label') : '')),
            Tables\Columns\TextColumn::make('zone_type')
                ->label(__('admin::taxzone.table.zone_type.label'))
                ->formatStateUsing(
                    fn ($state) => __("admin::taxzone.form.zone_type.options.{$state}")
                ),
            Tables\Columns\IconColumn::make('active')
                ->boolean()
                ->label(__('admin::taxzone.table.active.label')),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => TaxZoneResource\Pages\ListTaxZones::route('/'),
            'edit' => TaxZoneResource\Pages\EditTaxZone::route('/{record}/edit'),
            'create' => TaxZoneResource\Pages\CreateTaxZone::route('/create'),
        ];
    }
}
