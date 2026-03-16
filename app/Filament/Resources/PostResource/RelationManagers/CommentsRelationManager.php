<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Models\PostComment;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CommentsRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'comments';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Comments';
    }

    public function getDefaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Grid::make(2)->schema([
                    Select::make('customer_id')
                        ->relationship('customer', 'email')
                        ->searchable()
                        ->preload()
                        ->live(),
                    Select::make('parent_id')
                        ->relationship(
                            name: 'parent',
                            titleAttribute: 'body',
                            modifyQueryUsing: fn (Builder $query) => $query
                                ->where('post_id', $this->ownerRecord->getKey())
                                ->whereNull('parent_id')
                        )
                        ->searchable()
                        ->preload()
                        ->label('Reply to')
                        ->nullable(),
                ]),
                Components\Grid::make(2)->schema([
                    TextInput::make('guest_name')
                        ->maxLength(100)
                        ->required(fn (Get $get): bool => blank($get('customer_id'))),
                    TextInput::make('guest_email')
                        ->email()
                        ->maxLength(255)
                        ->required(fn (Get $get): bool => blank($get('customer_id'))),
                ]),
                Textarea::make('body')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                Components\Grid::make(2)->schema([
                    Toggle::make('is_approved')
                        ->label('Approved'),
                    Toggle::make('is_flagged')
                        ->label('Flagged'),
                ]),
            ]);
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                Tables\Columns\TextColumn::make('body')
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Customer')
                    ->placeholder('Guest'),
                Tables\Columns\TextColumn::make('guest_email')
                    ->label('Guest Email')
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('parent.body')
                    ->label('Reply To')
                    ->limit(30)
                    ->placeholder('Root'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean()
                    ->label('Approved'),
                Tables\Columns\IconColumn::make('is_flagged')
                    ->boolean()
                    ->label('Flagged'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Actions\Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->hidden(fn (PostComment $record): bool => $record->is_approved)
                    ->action(fn (PostComment $record): bool => $record->update(['is_approved' => true])),
                Actions\Action::make('flag')
                    ->icon('heroicon-o-flag')
                    ->color('warning')
                    ->label('Flag')
                    ->hidden(fn (PostComment $record): bool => $record->is_flagged)
                    ->action(fn (PostComment $record): bool => $record->update(['is_flagged' => true])),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('approveSelected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true])),
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
