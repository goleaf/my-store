<?php

namespace App\Observers;

use App\Jobs\Collections\UpdateProductPositions;
use App\Models\Contracts\Collection;

class CollectionObserver
{
    /**
     * Handle the \App\Models\Collection "updated" event.
     *
     * @return void
     */
    public function updated(Collection $collection)
    {
        UpdateProductPositions::dispatch($collection);
    }

    /**
     * Handle the \App\Models\Collection "deleting" event.
     *
     * @return void
     */
    public function deleting(Collection $collection)
    {
        /** @var \App\Models\Collection $collection */
        $collection->products()->detach();
        $collection->channels()->detach();
        $collection->urls()->delete();
        $collection->customerGroups()->detach();
        $collection->discounts()->detach();
    }
}
