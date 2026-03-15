<?php

namespace App\Filament\Widgets\Dashboard\Orders;

use App\Store\Facades\DB;
use App\Store\Models\Order;
use Carbon\CarbonInterface;
use Filament\Support\Facades\FilamentIcon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '60s';

    protected function getOrderQuery(\DateTime|CarbonInterface|null $from = null, \DateTime|CarbonInterface|null $to = null)
    {
        return Order::whereNotNull('placed_at')
            ->whereBetween('placed_at', [
                $from,
                $to,
            ]);
    }

    protected function getStats(): array
    {
        $date = now()->settings([
            'monthOverflow' => false,
        ]);

        $current30Days = $this->getOrderQuery(
            from: $date->clone()->subDays(30),
            to: $date->clone(),
        );

        $previous30Days = $this->getOrderQuery(
            from: $date->clone()->subDays(60),
            to: $date->clone()->subDays(30),
        );

        $current7Days = $this->getOrderQuery(
            from: $date->clone()->subDays(7),
            to: $date->clone(),
        );

        $previous7Days = $this->getOrderQuery(
            from: $date->clone()->subDays(14),
            to: $date->clone()->subDays(7),
        );

        $today = $this->getOrderQuery(
            from: $date->clone()->startOfDay(),
            to: $date->clone(),
        );

        $yesterday = $this->getOrderQuery(
            from: $date->clone()->subDay()->startOfDay(),
            to: $date->clone()->subDay(),
        );

        return [
            $this->getStatCount($today, $yesterday, 'stat_one'),
            $this->getStatCount($current7Days, $previous7Days, 'stat_two'),
            $this->getStatCount($current30Days, $previous30Days, 'stat_three'),
            $this->getStatTotal($today, $yesterday, 'stat_four'),
            $this->getStatTotal($current7Days, $previous7Days, 'stat_five'),
            $this->getStatTotal($current30Days, $previous30Days, 'stat_six'),
        ];
    }

    protected function getStatTotal($currentDate, $previousDate, $reference): Stat
    {
        $currentRow = $currentDate->with(['currency'])->select(
            DB::RAW('MAX(currency_code) as currency_code'),
            DB::RAW('sum(sub_total) as sub_total'),
        )->first();

        $previousRow = $previousDate->with(['currency'])->select(
            DB::RAW('MAX(currency_code) as currency_code'),
            DB::RAW('sum(sub_total) as sub_total')
        )->first();

        $currentSubTotal = $currentRow?->sub_total;
        $previousSubTotal = $previousRow?->sub_total;

        $currentValue = $currentSubTotal?->value ?? 0;
        $previousValue = $previousSubTotal?->value ?? 0;

        $percentage = $previousValue > 0
            ? round((($currentValue - $previousValue) / $previousValue) * 100)
            : ($currentValue > 0 ? 100 : 0);

        $increase = $percentage > 0;
        $neutral = $percentage === 0;
        $trend = $neutral ? 'neutral' : ($increase ? 'increase' : 'decrease');

        $valueFormatted = $currentSubTotal?->formatted ?? '0';
        $previousFormatted = $previousSubTotal?->formatted ?? '0';

        return Stat::make(
            label: __('admin::widgets.dashboard.orders.order_stats_overview.'.$reference.'.label'),
            value: $valueFormatted,
        )->description(
            __('admin::widgets.dashboard.orders.order_stats_overview.'.$reference.'.'.$trend, [
                'percentage' => abs($percentage),
                'total' => $previousFormatted,
            ])
        )->descriptionIcon(
            FilamentIcon::resolve(
                $trend == 'neutral' ? 'store::trending-neutral' : ($increase ? 'store::trending-up' : 'store::trending-down')
            )
        )
            ->color($trend == 'neutral' ? 'gray' : ($increase ? 'success' : 'danger'));
    }

    protected function getStatCount($currentDate, $previousDate, $reference): Stat
    {
        $currentCount = $currentDate->count();
        $previousCount = $previousDate->count();

        $percentage = $previousCount ?
            round((($currentCount - $previousCount) / $previousCount) * 100) :
            ($currentCount ? 100 : 0);

        $increase = $percentage > 0;
        $neutral = $percentage === 0;
        $trend = $neutral ? 'neutral' : ($increase ? 'increase' : 'decrease');

        $daysIncreased = $percentage > 0;

        return Stat::make(
            label: __('admin::widgets.dashboard.orders.order_stats_overview.'.$reference.'.label'),
            value: number_format($currentCount),
        )->description(
            __('admin::widgets.dashboard.orders.order_stats_overview.'.$reference.'.'.$trend, [
                'percentage' => abs($percentage),
                'count' => number_format($previousCount),
            ])
        )->descriptionIcon(
            FilamentIcon::resolve(
                $trend == 'neutral' ? '' : ($increase ? 'store::trending-up' : 'store::trending-down')
            )
        )
            ->color($trend == 'neutral' ? 'gray' : ($increase ? 'success' : 'danger'));
    }
}
