<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Events\CustomerUserEdited;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class UserRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'users';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::user.plural_label');
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('admin::user.table.name.label')),
            Tables\Columns\TextColumn::make('email')
                ->label(__('admin::user.table.email.label')),
        ])->actions([
            Actions\EditAction::make('edit')
                ->after(
                    fn (Model $record) => CustomerUserEdited::dispatch($record)
                )
                ->form([
                    SchemaComponents\Group::make([
                        TextInput::make('email')
                            ->label(
                                __('admin::user.form.email.label')
                            )
                            ->required()
                            ->email()
                            ->columnSpan(2),
                        TextInput::make('password')
                            ->label(
                                __('admin::user.form.password.label')
                            )
                            ->password()
                            ->minLength(8)
                            ->required(fn ($record) => blank($record))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->currentPassword(false)
                            ->confirmed(),
                        TextInput::make('password_confirmation')
                            ->label(
                                __('admin::user.form.password_confirmation.label')
                            )
                            ->password()
                            ->minLength(8)
                            ->dehydrated(false),
                    ])->columns(2),

                ]),
        ]);
    }
}
