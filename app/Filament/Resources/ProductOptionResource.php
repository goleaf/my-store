<?php

namespace App\Filament\Resources;

use App\Admin\Filament\Resources\ProductOptionResource\Pages;
use App\Admin\Filament\Resources\ProductOptionResource\RelationManagers;
use App\Store\Models\Contracts\ProductOption as ProductOptionContract;
use App\Store\Models\Language;
use App\Support\Forms\Components\TranslatedText;
use App\Support\Resources\BaseResource;
use App\Support\Tables\Columns\TranslatedTextColumn;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Actions;

class ProductOptionResource extends BaseResource
{
    protected static ?string $permission = 'settings';

    protected static ?string $model = ProductOptionContract::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::productoption.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::productoption.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-options');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.settings');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getLabelFormComponent(),
            static::getHandleFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return TranslatedText::make('name')
            ->label(__('admin::productoption.form.name.label'))
            ->required()
            ->maxLength(255)
            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                if ($operation !== 'create') {
                    return;
                }
                $set('handle', Str::slug($state[Language::getDefault()->code]));
            })
            ->live(onBlur: true)
            ->autofocus();
    }

    protected static function getLabelFormComponent(): Component
    {
        return TranslatedText::make('label')
            ->label(__('admin::productoption.form.label.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getHandleFormComponent(): Component
    {
        return Forms\Components\TextInput::make('handle')
            ->label(__('admin::productoption.form.handle.label'))
            ->required()
            ->maxLength(255)
            ->disabled(fn ($operation, $record) => $operation == 'edit' && (! $record->shared));
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns([
                TranslatedTextColumn::make('name')
                    ->label(__('admin::productoption.table.name.label'))
                    ->searchable(),
                TranslatedTextColumn::make('label')
                    ->label(__('admin::productoption.table.label.label')),
                Tables\Columns\TextColumn::make('handle')
                    ->label(__('admin::productoption.table.handle.label')),
                Tables\Columns\BooleanColumn::make('shared')
                    ->label(__('admin::productoption.table.shared.label')),
            ])
            ->filters([
                Tables\Filters\Filter::make('shared')
                    ->query(fn (Builder $query): Builder => $query->where('shared', true)),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->searchable();
    }

    public static function getRelations(): array
    {
        return [
            ProductOptionResource\RelationManagers\ValuesRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => ProductOptionResource\Pages\ListProductOptions::route('/'),
            'create' => ProductOptionResource\Pages\CreateProductOption::route('/create'),
            'edit' => ProductOptionResource\Pages\EditProductOption::route('/{record}/edit'),
        ];
    }
}
