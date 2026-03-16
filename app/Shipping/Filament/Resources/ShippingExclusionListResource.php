<?php

namespace App\Shipping\Filament\Resources;

use App\Shipping\Filament\Resources\ShippingExclusionListResource\Pages;
use App\Shipping\Filament\Resources\ShippingExclusionListResource\RelationManagers\ShippingExclusionRelationManager;
use App\Shipping\Models\Contracts\ShippingExclusionList;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Schemas\Schema;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class ShippingExclusionListResource extends BaseResource
{
    protected static ?string $model = ShippingExclusionList::class;

    protected static ?int $navigationSort = 1;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('storepanel.shipping::shippingexclusionlist.label');
    }

    public static function getPluralLabel(): string
    {
        return __('storepanel.shipping::shippingexclusionlist.label_plural');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::shipping-exclusion-lists');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('storepanel.shipping::plugin.navigation.group');
    }

    public static function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            SchemaComponents\Section::make()->schema(
                static::getMainFormComponents(),
            ),
        ]);
    }

    protected static function getDefaultRelations(): array
    {
        return [
            ShippingExclusionRelationManager::class,
        ];
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
        ];
    }

    public static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('storepanel.shipping::shippingexclusionlist.form.name.label'))
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

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(
                    __('storepanel.shipping::shippingexclusionlist.table.name.label')
                ),
            Tables\Columns\TextColumn::make('exclusions_count')
                ->label(
                    __('storepanel.shipping::shippingexclusionlist.table.exclusions_count.label')
                )
                ->counts('exclusions'),
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListShippingExclusionLists::route('/'),
            'edit' => Pages\EditShippingExclusionList::route('/{record}/edit'),
        ];
    }
}
