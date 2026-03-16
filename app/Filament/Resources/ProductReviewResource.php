<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductReviewResource\Pages\CreateProductReview;
use App\Filament\Resources\ProductReviewResource\Pages\EditProductReview;
use App\Filament\Resources\ProductReviewResource\Pages\ListProductReviews;
use App\Filament\Resources\ProductReviewResource\Schemas\ProductReviewForm;
use App\Filament\Resources\ProductReviewResource\Tables\ProductReviewsTable;
use App\Models\ProductReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductReviews::route('/'),
            'create' => CreateProductReview::route('/create'),
            'edit' => EditProductReview::route('/{record}/edit'),
        ];
    }
}
