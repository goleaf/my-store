<?php

namespace App\Filament\Resources\TaxRateResource\RelationManagers;

use App\Store\Models\TaxRateAmount;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class TaxRateAmountRelationManager extends RelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'taxRateAmounts';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            Select::make('tax_class_id')
                ->required()
                ->unique(
                    TaxRateAmount::modelClass(),
                    'tax_class_id',
                    ignoreRecord: true,
                    modifyRuleUsing: fn (Unique $rule) => $rule->when(
                        $this->getOwnerRecord(),
                        fn ($query, $value) => $query->where('tax_rate_id', $value->id)
                    )
                )
                ->relationship(name: 'taxClass', titleAttribute: 'name'),
            TextInput::make('percentage')->numeric()->required(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description(
                __('admin::relationmanagers.tax_rate_amounts.table.description')
            )
            ->paginated(false)
            ->headerActions([
                Tables\Actions\CreateAction::make('create'),
            ])->columns([
                Tables\Columns\TextColumn::make('taxClass.name')->label(
                    __('admin::relationmanagers.tax_rate_amounts.table.tax_class.label')
                ),
                Tables\Columns\TextColumn::make('percentage')->label(
                    __('admin::relationmanagers.tax_rate_amounts.table.percentage.label')
                ),
            ])->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
