<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionGroupResource\Pages;
use App\Models\Contracts\CollectionGroup;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions;
use Filament\Schemas\Components;

class CollectionGroupResource extends BaseResource
{
    protected static ?string $permission = 'catalog:manage-collections';

    protected static ?string $model = CollectionGroup::class;

    protected static ?int $navigationSort = 3;

    public static function getLabel(): string
    {
        return __('admin::collectiongroup.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::collectiongroup.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::collections');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.catalog');
    }

    public static function getDefaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make()->schema(
                    static::getMainFormComponents()
                )->columns(2),
            ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getHandleFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin::collectiongroup.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus()
            ->unique(ignoreRecord: true)
            ->live(onBlur: true)
            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                if ($operation !== 'create') {
                    return;
                }
                $set('handle', Str::slug($state));
            });
    }

    protected static function getHandleFormComponent(): Component
    {
        return Forms\Components\TextInput::make('handle')
            ->label(__('admin::collectiongroup.form.handle.label'))
            ->unique(ignoreRecord: true)
            ->required()
            ->maxLength(255);
    }

    public static function table(Table $table): Table
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
                ->label(__('admin::collectiongroup.table.name.label')),
            Tables\Columns\TextColumn::make('handle')
                ->label(__('admin::collectiongroup.table.handle.label')),
            Tables\Columns\TextColumn::make('collections_count')
                ->counts('collections')
                ->formatStateUsing(
                    fn ($state) => number_format($state, 0)
                )
                ->label(__('admin::collectiongroup.table.collections_count.label')),
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
            'index' => CollectionGroupResource\Pages\ListCollectionGroups::route('/'),
            'edit' => CollectionGroupResource\Pages\EditCollectionGroup::route('/{record}/edit'),
        ];
    }
}
