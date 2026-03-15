<?php

namespace App\Admin\Filament\Widgets\Dashboard\Orders;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use App\Admin\Filament\Resources\OrderResource;
use App\Store\Models\Order;

class LatestOrdersTable extends TableWidget
{
    protected function getTablePollingInterval(): ?string
    {
        return '60s';
    }

    protected int|string|array $columnSpan = 'full';

    public static function getHeading(): ?string
    {
        return __('admin::widgets.dashboard.orders.latest_orders.heading');
    }

    public function table(Table $table): Table
    {
        return $table->query(function () {
            return Order::with(['currency'])->orderBy('placed_at', 'desc')->orderBy('created_at', 'desc')->limit(10);
        })->columns(
            OrderResource::getTableColumns()
        )->paginated(false)->searchable(false)
            ->heading($this->getHeading());
    }
}
