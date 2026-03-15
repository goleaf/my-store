<?php

namespace App\Admin\Filament\Widgets\Dashboard\Orders;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use App\Store\Facades\DB;
use App\Store\Models\CustomerGroup;
use App\Store\Models\Order;
use Illuminate\Contracts\Support\Htmlable;

class AverageOrderValueChart extends ChartWidget
{
    protected ?string $pollingInterval = '60s';

    protected ?string $heading = null;

    public function getHeading(): Htmlable|string|null
    {
        return $this->heading ?? __('admin::widgets.dashboard.orders.average_order_value.heading');
    }

    protected function getOrderQuery(\DateTime|CarbonInterface|null $from = null, \DateTime|CarbonInterface|null $to = null)
    {
        return Order::whereNotNull('placed_at')
            ->with(['currency'])
            ->whereBetween('placed_at', [$from, $to]);
    }

    protected function getData(): array
    {
        $customerGroups = CustomerGroup::get();
        $date = now()->settings(['monthOverflow' => false]);
        $from = $date->clone()->subYear();
        $period = CarbonPeriod::create($from, '1 month', $date);

        $datasets = $customerGroups->map(function ($group) use ($date, $from, $period) {
            $query = $this->getOrderQuery($from, $date);
            $guestOrders = collect();

            if ($group->default) {
                $guestOrders = $query->clone()->with(['currency'])->whereNull('user_id')->whereNull('customer_id')
                    ->select(
                        DB::RAW('ROUND(AVG(sub_total), 0) as sub_total'),
                        DB::RAW(db_date('placed_at', '%Y-%m', 'date'))
                    )->groupBy(DB::RAW('date'))->orderBy(DB::RAW('date'), 'desc')->get();
            }

            $result = $query->whereHas(
                'customer',
                fn ($relation) => $relation->whereHas(
                    'customerGroups',
                    fn ($subRelation) => $subRelation->where("{$group->getTable()}.id", '=', $group->id)
                )
            )->select(
                DB::RAW('ROUND(AVG(sub_total), 0) as sub_total'),
                DB::RAW(db_date('placed_at', '%Y-%m', 'date'))
            )->groupBy(DB::RAW('date'))->orderBy(DB::RAW('date'), 'desc')->get();

            $merged = collect([...$result, ...$guestOrders]);
            $data = collect();
            foreach ($period as $dateItem) {
                $row = $merged->first(fn ($month) => $month->date == $dateItem->format('Y-m'));
                $data->push($row?->sub_total->decimal ?: 0);
            }

            return [
                'label' => $group->name,
                'data' => $data->toArray(),
            ];
        })->toArray();

        $labels = [];
        foreach ($period as $dateItem) {
            $labels[] = $dateItem->format('F Y');
        }

        return [
            'datasets' => array_values($datasets),
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
