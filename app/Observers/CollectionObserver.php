<?php

namespace App\Store\Observers;

use App\Store\Jobs\Collections\UpdateProductPositions;
use App\Store\Models\Collection;
use App\Store\Models\Contracts\Collection as CollectionContract;

class CollectionObserver
{
    /**
     * Handle the Collection "updated" event.
     *
     * @return void
     */
    public function updated(CollectionContract $collection)
    {
        UpdateProductPositions::dispatch($collection);
    }

    /**
     * Handle the Collection "deleting" event.
     *
     * @return void
     */
    public function deleting(CollectionContract $collection)
    {
        /** @var Collection $collection */
        $collection->products()->detach();
        $collection->channels()->detach();
        $collection->urls()->delete();
        $collection->customerGroups()->detach();
        $collection->discounts()->detach();
    }
}
