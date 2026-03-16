<?php

namespace App\Support;

use Filament\Support\Colors\Color;
class OrderStatus
{
    protected static array $cachedStatusColor = [];

    protected static array $cachedStatusLabel = [];

    public static function getLabel($status): string
    {
        return static::$cachedStatusLabel[$status] ??= filled($label = config('store.orders.statuses.'.$status.'.label')) ? $label : (filled($status) ? $status : 'N/A');
    }

    public static function getColor($status): array
    {
        return static::$cachedStatusColor[$status] ??= Color::hex(filled($color = config('store.orders.statuses.'.$status.'.color')) ? $color : '#7C7C7C');
    }
}
