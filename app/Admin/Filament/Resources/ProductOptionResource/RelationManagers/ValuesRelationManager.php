<?php

namespace App\Admin\Filament\Resources\ProductOptionResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Admin\Support\Forms\Components\TranslatedText;
use App\Admin\Support\RelationManagers\BaseRelationManager;
use App\Admin\Support\Tables\Columns\TranslatedTextColumn;

class ValuesRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'values';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::relationmanagers.values.title');
    }

    public function getTableRecordTitle(Model $record): ?string
    {
        return $record->translate('name');
    }

    public function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                TranslatedText::make('name')
                    ->label(__('admin::relationmanagers.values.form.name.label'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table

            ->columns([
                TranslatedTextColumn::make('name')
                    ->label(__('admin::relationmanagers.values.table.name.label')),
                Tables\Columns\TextColumn::make('position')
                    ->label(__('admin::relationmanagers.values.table.position.label')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position', 'asc')
            ->reorderable('position');
    }
}
