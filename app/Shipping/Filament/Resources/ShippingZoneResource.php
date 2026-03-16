<?php

namespace App\Shipping\Filament\Resources;

use App\Filament\Components\Shout;
use App\Models\Country;
use App\Models\State;
use App\Shipping\Enums\ShippingZoneType;
use App\Shipping\Filament\Resources\ShippingZoneResource\Pages;
use App\Shipping\Models\Contracts\ShippingZone;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Pages\Page;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Filament\Actions;
use Filament\Schemas\Components;

class ShippingZoneResource extends BaseResource
{
    protected static ?string $model = ShippingZone::class;

    protected static ?int $navigationSort = 1;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('admin.shipping::shippingzone.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin.shipping::shippingzone.label_plural');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::shipping-zones');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.shipping::plugin.navigation.group');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditShippingZone::class,
            Pages\ManageShippingRates::class,
            Pages\ManageShippingExclusions::class,
        ]);
    }

    public static function getDefaultForm(Schema $schema): Schema
    {
        return $schema->components([
            Components\Section::make()->schema(
                static::getMainFormComponents(),
            ),
        ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getTypeFormComponent(),
            static::getCountryFormComponent(),
            static::getPostcodesFormComponent(),
            static::getStatesFormComponent(),
            static::getCountriesFormComponent(),
            Shout::make('unrestricted')->content(
                __('admin.shipping::shippingzone.form.unrestricted.content')
            )->hidden(
                fn (Get $get) => $get('type') !== ShippingZoneType::Unrestricted->value
            ),
        ];
    }

    public static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin.shipping::shippingzone.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    public static function getTypeFormComponent(): Component
    {
        return Forms\Components\Select::make('type')
            ->label(__('admin.shipping::shippingzone.form.type.label'))
            ->required()
            ->options(ShippingZoneType::options())->live();
    }

    protected static function getCountryFormComponent(): Component
    {
        return Forms\Components\Select::make('country')
            ->label(__('admin.shipping::shippingzone.form.country.label'))
            ->dehydrated(false)
            ->visible(
                fn (Get $get) => ! in_array($get('type'), [
                    ShippingZoneType::Countries->value,
                    ShippingZoneType::Unrestricted->value,
                ], true)
            )
            ->options(Country::query()->orderBy('name')->pluck('name', 'id'))

            ->required()
            ->searchable()
            ->loadStateFromRelationshipsUsing(static function (Forms\Components\Select $component, Model $record): void {
                $record->loadMissing('countries');

                /** @var Collection $relatedModels */
                $country = $record->countries->first();

                $component->state(
                    $country?->id
                );
            })->getOptionLabelsUsing(static function (Model $record): array {
                $record->loadMissing('countries.country');

                return $record->countries
                    ->pluck('country.name', 'country.id')
                    ->toArray();
            })
            ->saveRelationshipsUsing(static function (Model $record, $state) {
                $record->countries()->sync(filled($state) ? [$state] : []);
            });
    }

    protected static function getCountriesFormComponent(): Component
    {
        return Forms\Components\Select::make('countries')
            ->label(__('admin.shipping::shippingzone.form.countries.label'))
            ->visible(fn (Get $get) => $get('type') === ShippingZoneType::Countries->value)
            ->dehydrated(false)
            ->options(Country::query()->orderBy('name')->pluck('name', 'id'))
            ->multiple()
            ->required()
            ->loadStateFromRelationshipsUsing(static function (Forms\Components\Select $component, Model $record): void {
                $record->loadMissing('countries');
                /** @var Collection $relatedModels */
                $relatedModels = $record->countries;

                $component->state(
                    $relatedModels
                        ->pluck('id')
                        ->map(static fn ($key): string => strval($key))
                        ->toArray(),
                );
            })->getOptionLabelsUsing(static function (Model $record): array {
                $record->loadMissing('countries');

                return $record->countries
                    ->pluck('name', 'id')
                    ->toArray();
            })
            ->saveRelationshipsUsing(static function (Model $record, $state) {
                $record->countries()->sync($state);
            });
    }

    protected static function getStatesFormComponent(): Component
    {
        return Forms\Components\Select::make('states')
            ->label(__('admin.shipping::shippingzone.form.states.label'))
            ->visible(fn (Get $get) => $get('type') === ShippingZoneType::States->value)
            ->dehydrated(false)
            ->options(fn (Get $get) => State::query()->where('country_id', $get('country'))->orderBy('name')->pluck('name', 'id'))
            ->multiple()
            ->required()
            ->loadStateFromRelationshipsUsing(static function (Forms\Components\Select $component, Model $record): void {
                $record->loadMissing('states');

                /** @var Collection $relatedModels */
                $relatedModels = $record->states;

                $component->state(
                    $relatedModels
                        ->pluck('id')
                        ->map(static fn ($key): string => strval($key))
                        ->toArray(),
                );
            })->getOptionLabelsUsing(static function (Model $record): array {
                $record->loadMissing('states');

                return $record->states
                    ->pluck('name', 'id')
                    ->toArray();
            })
            ->saveRelationshipsUsing(static function (Model $record, $state, $get) {
                $record->states()->sync($state);
            });
    }

    protected static function getPostcodesFormComponent(): Component
    {
        return Forms\Components\Textarea::make('postcodes')
            ->label(__('admin.shipping::shippingzone.form.postcodes.label'))
            ->visible(fn (Get $get) => $get('type') === ShippingZoneType::Postcodes->value)
            ->dehydrated(false)
            ->rows(10)
            ->helperText(__('admin.shipping::shippingzone.form.postcodes.helper'))
            ->required()
            ->afterStateHydrated(static function (Forms\Components\Textarea $component, Model $record): void {
                /** @var Collection $relatedModels */
                $relatedModels = $record->postcodes;

                $component->state(
                    $relatedModels
                        ->pluck('postcode')
                        ->join("\n"),
                );
            })
            ->saveRelationshipsUsing(static function (Model $record, $state): void {
                static::syncPostcodes($record, $state);

                $record->states()->detach();
            });
    }

    private static function syncPostcodes(ShippingZone $shippingZone, $postcodes): void
    {
        $postcodes = collect(
            explode(
                "\n",
                str_replace(' ', '', $postcodes)
            )
        )->unique()->filter();

        $shippingZone->postcodes()->delete();

        $shippingZone->postcodes()->createMany(
            $postcodes->map(function ($postcode) {
                return [
                    'postcode' => $postcode,
                ];
            })
        );
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
                ->label(
                    __('admin.shipping::shippingzone.table.name.label')
                ),
            Tables\Columns\TextColumn::make('type')
                ->label(
                    __('admin.shipping::shippingzone.table.type.label')
                )
                ->formatStateUsing(
                    fn ($state) => ShippingZoneType::labelFor($state)
                ),
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
            'index' => Pages\ListShippingZones::route('/'),
            'edit' => Pages\EditShippingZone::route('/{record}/edit'),
            'rates' => Pages\ManageShippingRates::route('/{record}/rates'),
            'exclusions' => Pages\ManageShippingExclusions::route('/{record}/exclusions'),
        ];
    }
}
