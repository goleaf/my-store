<?php

namespace App\Support\Pages;

use App\Base\Traits\Searchable;
use App\Support\Pages\Concerns\ExtendsFooterWidgets;
use App\Support\Pages\Concerns\ExtendsHeaderActions;
use App\Support\Pages\Concerns\ExtendsHeaderWidgets;
use App\Support\Pages\Concerns\ExtendsHeadings;
use App\Support\Pages\Concerns\ExtendsTablePagination;
use App\Support\Pages\Concerns\ExtendsTabs;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Support\Concerns\CallsHooks;

abstract class BaseListRecords extends ListRecords
{
    use ExtendsFooterWidgets;
    use ExtendsHeaderActions;
    use ExtendsHeaderWidgets;
    use ExtendsHeadings;
    use ExtendsTablePagination;
    use ExtendsTabs;
    use CallsHooks;

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        $scoutEnabled = config('store.panel.scout_enabled', false);
        $isScoutSearchable = in_array(Searchable::class, class_uses_recursive(static::getModel()));

        $this->applyColumnSearchesToTableQuery($query);

        if (! $scoutEnabled || ! $isScoutSearchable) {
            $this->applyGlobalSearchToTableQuery($query);
        }

        if (
            filled($search = $this->getTableSearch()) &&
            $scoutEnabled &&
            $isScoutSearchable
        ) {
            $ids = collect(static::getModel()::search($search)->take(100)->keys())->map(
                fn ($result) => str_replace(static::getModel().'::', '', $result)
            );

            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $query->whereIn(
                'id',
                $ids
            );

            $query->when(
                ! $ids->isEmpty(),
                fn ($query) => $query->orderBySequence($ids->toArray())
            );
        }

        return $query;
    }
}
