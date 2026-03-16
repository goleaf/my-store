<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostCategoryResource\Pages\CreatePostCategory;
use App\Filament\Resources\PostCategoryResource\Pages\EditPostCategory;
use App\Filament\Resources\PostCategoryResource\Pages\ListPostCategories;
use App\Filament\Resources\PostCategoryResource\Schemas\PostCategoryForm;
use App\Filament\Resources\PostCategoryResource\Tables\PostCategoriesTable;
use App\Models\PostCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PostCategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-folder-tree';

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PostCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostCategoriesTable::configure($table);
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
            'index' => ListPostCategories::route('/'),
            'create' => CreatePostCategory::route('/create'),
            'edit' => EditPostCategory::route('/{record}/edit'),
        ];
    }
}
