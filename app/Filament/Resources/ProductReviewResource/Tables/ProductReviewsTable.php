<?php

namespace App\Filament\Resources\ProductReviewResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->sortable(),
                TextColumn::make('customer.email')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rating_flavor')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rating_value')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rating_scent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('helpful_count')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_verified_purchase')
                    ->boolean(),
                IconColumn::make('is_approved')
                    ->boolean(),
                IconColumn::make('is_flagged')
                    ->boolean(),
                TextColumn::make('admin_replied_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
