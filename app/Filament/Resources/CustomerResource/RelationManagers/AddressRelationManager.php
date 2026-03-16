<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Events\CustomerAddressEdited;
use App\Models\Contracts\Address;
use App\Models\State;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Schemas\Components;

class AddressRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'addresses';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::address.plural_label');
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table
            ->heading(
                __('admin::address.plural_label')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')->label(
                    __('admin::address.table.title.label')
                ),
                Tables\Columns\TextColumn::make('first_name')->label(
                    __('admin::address.table.first_name.label')
                ),
                Tables\Columns\TextColumn::make('last_name')->label(
                    __('admin::address.table.last_name.label')
                ),
                Tables\Columns\TextColumn::make('company_name')->label(
                    __('admin::address.table.company_name.label')
                ),
                Tables\Columns\TextColumn::make('tax_identifier')->label(
                    __('admin::address.table.tax_identifier.label')
                ),
                Tables\Columns\TextColumn::make('line_one')->label(
                    __('admin::address.table.line_one.label')
                )->description(function (Model $record) {
                    if (! $record->line_two && $record->line_three) {
                        return $record->line_three;
                    }
                    if (! $record->line_three) {
                        return $record->line_two;
                    }

                    return "{$record->line_two}, {$record->line_three}";
                }),
                Tables\Columns\TextColumn::make('city')->label(
                    __('admin::address.table.city.label')
                ),
                Tables\Columns\TextColumn::make('state')->label(
                    __('admin::address.table.state.label')
                ),
                Tables\Columns\TextColumn::make('postcode')->label(
                    __('admin::address.table.postcode.label')
                ),
                Tables\Columns\TextColumn::make('contact_email')->label(
                    __('admin::address.table.contact_email.label')
                ),
                Tables\Columns\TextColumn::make('contact_phone')->label(
                    __('admin::address.table.contact_phone.label')
                ),
            ])->actions([
                Actions\EditAction::make('editAddress')
                    ->after(
                        fn (Model $record) => CustomerAddressEdited::dispatch($record)
                    )
                    ->fillForm(fn (Address $record): array => [
                        'title' => $record->title,
                        'first_name' => $record->first_name,
                        'last_name' => $record->last_name,
                        'company_name' => $record->company_name,
                        'tax_identifier' => $record->tax_identifier,
                        'line_one' => $record->line_one,
                        'line_two' => $record->line_two,
                        'line_three' => $record->line_three,
                        'city' => $record->city,
                        'state' => $record->state,
                        'postcode' => $record->postcode,
                        'contact_email' => $record->contact_email,
                        'contact_phone' => $record->contact_phone,
                    ])
                    ->form([
                        Components\Group::make()->schema([
                            Forms\Components\TextInput::make('title')->label(
                                __('admin::address.form.title.label')
                            )->columnSpan(1),
                            Forms\Components\TextInput::make('first_name')->label(
                                __('admin::address.form.first_name.label')
                            )->columnSpan(2),
                            Forms\Components\TextInput::make('last_name')->label(
                                __('admin::address.form.last_name.label')
                            )->columnSpan(2),
                        ])->columns(5),
                        Forms\Components\TextInput::make('company_name')->label(
                            __('admin::address.form.company_name.label')
                        ),
                        Forms\Components\TextInput::make('tax_identifier')->label(
                            __('admin::address.form.tax_identifier.label')
                        ),
                        Components\Group::make()->schema([
                            Forms\Components\TextInput::make('line_one')->label(
                                __('admin::address.form.line_one.label')
                            ),
                            Forms\Components\TextInput::make('line_two')->label(
                                __('admin::address.form.line_two.label')
                            ),
                            Forms\Components\TextInput::make('line_three')->label(
                                __('admin::address.form.line_three.label')
                            ),
                        ])->columns(3),
                        Components\Group::make()->schema([
                            Forms\Components\Select::make('country_id')->label(
                                __('admin::address.form.country_id.label')
                            )->relationship(
                                name: 'country',
                            )->getOptionLabelFromRecordUsing(function (Model $record) {
                                $name = $record->native ?: $record->name;

                                return "{$record->emoji} $name";
                            }),
                            Forms\Components\TextInput::make('state')->label(
                                __('admin::address.form.state.label')
                            )->datalist(function ($record) {
                                return State::whereCountryId($record->country_id)
                                    ->where('name', 'LIKE', "%{$record->state}%")
                                    ->get()->map(
                                        fn ($state) => $state->name
                                    );
                            }),
                        ])->columns(2),
                        Components\Group::make()->schema([
                            Forms\Components\TextInput::make('city')->label(
                                __('admin::address.form.city.label')
                            ),
                            Forms\Components\TextInput::make('postcode')->label(
                                __('admin::address.form.postcode.label')
                            ),
                        ])->columns(2),
                        Components\Group::make()->schema([
                            Forms\Components\TextInput::make('contact_email')->label(
                                __('admin::address.form.contact_email.label')
                            ),
                            Forms\Components\TextInput::make('contact_phone')->label(
                                __('admin::address.form.contact_phone.label')
                            ),
                        ])->columns(2),
                    ]),
                Actions\DeleteAction::make('deleteAddress'),
            ]);
    }
}
