<?php

namespace App\Admin\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Admin\Filament\Resources\OrderResource;
use App\Admin\Filament\Resources\OrderResource\Pages\ManageOrder;
use App\Admin\Support\RelationManagers\BaseRelationManager;
use App\Store\Models\Contracts\Order as OrderContract;

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
            Tables\Actions\Action::make('viewOrder')
                ->url(fn (OrderContract $record): string => ManageOrder::getUrl(['record' => $record])),
        ]);
    }
}
