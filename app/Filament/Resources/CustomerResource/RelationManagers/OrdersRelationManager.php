<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Pages\ManageOrder;
use App\Models\Contracts\Order as OrderContract;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;

class OrdersRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'orders';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::order.plural_label');
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table->columns(
            OrderResource::getTableColumns()
        )->modifyQueryUsing(
            fn (Builder $query): Builder => $query->with(['currency'])
        )->actions([
            Actions\Action::make('viewOrder')
                ->url(fn (OrderContract $record): string => ManageOrder::getUrl(['record' => $record])),
        ]);
    }
}
