<?php

namespace App\Filament\Resources\OrderResource\Concerns;

use App\Support\Infolists\Components\Timeline;
use Filament\Infolists;

trait DisplaysOrderTimeline
{
    public static function getTimelineInfolist(): Infolists\Components\Component
    {
        return self::callStaticStoreHook('extendTimelineInfolist', static::getDefaultTimelineInfolist());
    }

    public static function getDefaultTimelineInfolist(): Infolists\Components\Component
    {
        return Infolists\Components\Grid::make()
            ->schema([
                Timeline::make('timeline')
                    ->label(__('admin::order.infolist.timeline.label')),
            ]);
    }
}
