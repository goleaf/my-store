<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributeGroupResource\Pages;
use App\Filament\Resources\AttributeGroupResource\RelationManagers;
use App\Facades\AttributeManifest;
use App\Models\Contracts\AttributeGroup as AttributeGroupContract;
use App\Models\Language;
use App\Support\Resources\BaseResource;
use App\Support\Tables\Columns\TranslatedTextColumn;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class AttributeGroupResource extends BaseResource
{
    protected static ?string $permission = 'settings:manage-attributes';

    protected static ?string $model = AttributeGroupContract::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::attributegroup.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::attributegroup.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::attributes');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.settings');
    }

    public static function getDefaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make()->schema(
                    static::getMainFormComponents()
                ),
            ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getAttributableTypeFormComponent(),
            static::getNameFormComponent(),
            static::getHandleFormComponent(),
            static::getPositionFormComponent(),
        ];
    }

    protected static function getAttributableTypeFormComponent(): Component
    {
        return Forms\Components\Select::make('attributable_type')
            ->label(__('admin::attributegroup.form.attributable_type.label'))
            ->options(function () {
                return AttributeManifest::getTypes()->mapWithKeys(
                    fn ($type) => [
                        \App\Facades\ModelManifest::getMorphMapKey($type) => class_basename($type),
                    ]
                );
            })
            ->required()
            ->autofocus();
    }

    protected static function getNameFormComponent(): Component
    {
        return \App\Support\Forms\Components\TranslatedText::make('name')
            ->label(__('admin::attributegroup.form.name.label'))
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

    protected static function getHandleFormComponent(): Component
    {
        return Forms\Components\TextInput::make('handle')
            ->label(__('admin::attributegroup.form.handle.label'))
            ->required()
            ->maxLength(255);
    }

    protected static function getPositionFormComponent(): Component
    {
        return Forms\Components\TextInput::make('position')
            ->label(__('admin::attributegroup.form.position.label'))
            ->numeric()
            ->minValue(1)
            ->maxValue(100)
            ->required();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attributable_type')
                    ->label(__('admin::attributegroup.table.attributable_type.label')),
                TranslatedTextColumn::make('name')
                    ->label(__('admin::attributegroup.table.name.label')),
                Tables\Columns\TextColumn::make('handle')
                    ->label(__('admin::attributegroup.table.handle.label')),
                Tables\Columns\TextColumn::make('position')
                    ->label(__('admin::attributegroup.table.position.label'))
                    ->sortable(),
            ])
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
            ])
            ->defaultSort('position', 'asc')
            ->reorderable('position');
    }

    public static function getRelations(): array
    {
        return [
            AttributeGroupResource\RelationManagers\AttributesRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => AttributeGroupResource\Pages\ListAttributeGroups::route('/'),
            'create' => AttributeGroupResource\Pages\CreateAttributeGroup::route('/create'),
            'edit' => AttributeGroupResource\Pages\EditAttributeGroup::route('/{record}/edit'),
        ];
    }
}
