<?php

namespace App\Filament\Resources\OrderResource\Concerns;

use App\Support\Infolists\Components\Timeline;
use Filament\Infolists;
use Filament\Schemas\Components;

trait DisplaysOrderTimeline
{
    public static function getTimelineInfolist(): Components\Component
    {
        return self::callStaticStoreHook('extendTimelineInfolist', static::getDefaultTimelineInfolist());
    }

    public static function getDefaultTimelineInfolist(): Components\Component
    {
        return Components\Grid::make()
            ->schema([
                Timeline::make('timeline')
                    ->label(__('admin::order.infolist.timeline.label')),
            ]);
    }
}
