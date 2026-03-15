<?php

namespace App\Filament\Resources;

use App\Admin\Filament\Resources\LanguageResource\Pages;
use App\Store\Models\Contracts\Language as LanguageContract;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class LanguageResource extends BaseResource
{
    protected static ?string $permission = 'settings:core';

    protected static ?string $model = LanguageContract::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::language.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::language.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::languages');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.settings');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getCodeFormComponent(),
            static::getDefaultFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin::language.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getCodeFormComponent(): Component
    {
        return Forms\Components\TextInput::make('code')
            ->label(__('admin::language.form.code.label'))
            ->required()
            ->minLength(2)
            ->maxLength(5);
    }

    protected static function getDefaultFormComponent(): Component
    {
        return Forms\Components\Toggle::make('default')
            ->label(__('admin::language.form.default.label'));
    }

    protected static function getDefaultTable(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('admin::language.table.name.label'))
                ->formatStateUsing(fn ($state, Model $record) => $state.($record->default ? ' • '.__('admin::language.table.default.label') : '')),
            Tables\Columns\TextColumn::make('code')
                ->label(__('admin::language.table.code.label')),
        ]);
    }

    public static function getDefaultRelations(): array
    {
        return [
            //
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => LanguageResource\Pages\ListLanguages::route('/'),
            'create' => LanguageResource\Pages\CreateLanguage::route('/create'),
            'edit' => LanguageResource\Pages\EditLanguage::route('/{record}/edit'),
        ];
    }
}
