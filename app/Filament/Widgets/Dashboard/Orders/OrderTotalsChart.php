<?php

namespace App\Filament\Widgets\Dashboard\Orders;

use App\Store\Facades\DB;
use App\Store\Models\Order;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Contracts\Support\Htmlable;

class OrderTotalsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $pollingInterval = '60s';

    protected ?string $heading = null;

    public function getHeading(): Htmlable|string|null
    {
        return $this->heading ?? __('admin::widgets.dashboard.orders.order_totals_chart.heading');
    }

    protected function getOrderQuery(\DateTime|CarbonInterface|null $from = null, \DateTime|CarbonInterface|null $to = null)
    {
        return Order::whereNotNull('placed_at')
            ->with(['currency'])
            ->whereBetween('placed_at', [$from, $to]);
    }

    protected function getTotalsForPeriod($from, $to): \Illuminate\Support\Collection
    {
        $currentPeriod = collect();
        $period = CarbonPeriod::create($from, '1 month', $to);

        $results = $this->getOrderQuery($from, $to)
            ->select(
                DB::RAW('SUM(sub_total) as sub_total'),
                DB::RAW(db_date('placed_at', '%M', 'month')),
                DB::RAW(db_date('placed_at', '%Y', 'year')),
                DB::RAW(db_date('placed_at', '%Y%m', 'monthstamp'))
            )->groupBy(
                DB::RAW('month'),
                DB::RAW('year'),
                DB::RAW('monthstamp'),
                DB::RAW(db_date('placed_at', '%Y-%m')),
            )->orderBy(DB::RAW(db_date('placed_at', '%Y-%m')), 'desc')->get();

        foreach ($period as $date) {
            $report = $results->first(fn ($month) => $month->monthstamp == $date->format('Ym'));
            $currentPeriod->push((object) [
                'sub_total' => $report?->sub_total->decimal ?: 0,
                'month' => $date->format('F'),
            ]);
        }

        return $currentPeriod;
    }

    protected function getData(): array
    {
        $date = now()->settings(['monthOverflow' => false]);
        $from = $date->clone()->subYear();

        $currentPeriod = $this->getTotalsForPeriod($from, $date);
        $previousPeriod = $this->getTotalsForPeriod($from->clone()->subYear(), $date->clone()->subYear());

        return [
            'datasets' => [
                [
                    'label' => __('admin::widgets.dashboard.orders.order_totals_chart.series_one.label'),
                    'data' => $currentPeriod->pluck('sub_total')->toArray(),
                ],
                [
                    'label' => __('admin::widgets.dashboard.orders.order_totals_chart.series_two.label'),
                    'data' => $previousPeriod->pluck('sub_total')->toArray(),
                ],
            ],
            'labels' => $currentPeriod->map(fn ($record) => $record->month)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
