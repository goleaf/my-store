<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Models\Contracts\Tag;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Schemas\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class TagResource extends BaseResource
{
    protected static ?string $permission = 'settings';

    protected static ?string $model = Tag::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::tag.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::tag.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::tags');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.settings');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getValueFormComponent(),
        ];
    }

    protected static function getValueFormComponent(): Component
    {
        return Forms\Components\TextInput::make('value')
            ->label(__('admin::tag.form.value.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
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
            Tables\Columns\TextColumn::make('value')
                ->label(__('admin::tag.table.value.label')),
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
            'index' => TagResource\Pages\ListTags::route('/'),
            'create' => TagResource\Pages\CreateTag::route('/create'),
            'edit' => TagResource\Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
