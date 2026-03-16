<?php

namespace App\Filament\Resources\AttributeGroupResource\RelationManagers;

use App\Models\Language;
use App\Support\Facades\AttributeData;
use App\Support\Forms\Components\TranslatedText;
use App\Support\RelationManagers\BaseRelationManager;
use App\Support\Tables\Columns\TranslatedTextColumn;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class AttributesRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'attributes';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::attribute.plural_label');
    }

    protected static ?string $recordTitleAttribute = 'name.en';  // TODO: localise somehow

    public function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                TranslatedText::make('name')
                    ->label(
                        __('admin::attribute.form.name.label')
                    )
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('handle', Str::slug($state[Language::getDefault()->code]));
                    }),
                TranslatedText::make('description')
                    ->label(
                        __('admin::attribute.form.description.label')
                    )
                    ->helperText(
                        __('admin::attribute.form.description.helper')
                    )
                    ->afterStateHydrated(fn ($state, $component) => $state ?: $component->state([Language::getDefault()->code => null]))
                    ->maxLength(255),
                Forms\Components\TextInput::make('handle')
                    ->label(
                        __('admin::attribute.form.handle.label')
                    )->dehydrated()
                    ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, RelationManager $livewire) {
                        return $rule->where('attribute_group_id', $livewire->ownerRecord->id);
                    })->disabled(
                        fn (?Model $record) => (bool) $record
                    )
                    ->required(),
                SchemaComponents\Grid::make(3)->schema([
                    Forms\Components\Toggle::make('searchable')
                        ->label(
                            __('admin::attribute.form.searchable.label')
                        )->default(false),
                    Forms\Components\Toggle::make('filterable')
                        ->label(
                            __('admin::attribute.form.filterable.label')
                        )->default(false),
                    Forms\Components\Toggle::make('required')
                        ->label(
                            __('admin::attribute.form.required.label')
                        )->default(false),
                ]),
                Forms\Components\Select::make('type')->label(
                    __('admin::attribute.form.type.label')
                )->disabled(
                    fn (?Model $record) => (bool) $record
                )->options(
                    AttributeData::getFieldTypes()->mapWithKeys(function ($fieldType) {
                        $langKey = strtolower(
                            class_basename($fieldType)
                        );

                        return [
                            $fieldType => __("admin::fieldtypes.{$langKey}.label"),
                        ];
                    })->toArray()
                )->required()->live()->afterStateUpdated(fn (Forms\Components\Select $component) => $component
                    ->getContainer()
                    ->getComponent('configuration')
                    ->getChildComponentContainer()

                    ->fill()),
                Forms\Components\TextInput::make('validation_rules')->label(
                    __('admin::attribute.form.validation_rules.label')
                )
                    ->string()
                    ->nullable()
                    ->helperText(
                        __('admin::attribute.form.validation_rules.helper')
                    ),
                SchemaComponents\Grid::make(1)
                    ->schema(function (Forms\Get $get) {
                        return AttributeData::getConfigurationFields($get('type'));
                    })->key('configuration')->statePath('configuration'),
            ]);
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns([
                TranslatedTextColumn::make('name')->label(
                    __('admin::attribute.table.name.label')
                ),
                Tables\Columns\TextColumn::make('description.en')->label(
                    __('admin::attribute.table.description.label')
                ),
                Tables\Columns\TextColumn::make('handle')
                    ->label(
                        __('admin::attribute.table.handle.label')
                    ),
                Tables\Columns\TextColumn::make('type')->label(
                    __('admin::attribute.table.type.label')
                ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()->mutateFormDataUsing(function (array $data, RelationManager $livewire) {
                    $data['configuration'] = $data['configuration'] ?? [];
                    $data['system'] = false;
                    $data['attribute_type'] = $livewire->ownerRecord->attributable_type;
                    $data['position'] = $livewire->ownerRecord->attributes()->count() + 1;

                    return $data;
                }),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position', 'asc')
            ->reorderable('position');
    }
}
