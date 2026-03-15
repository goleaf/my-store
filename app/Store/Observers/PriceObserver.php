<?php

namespace App\Store\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Store\Jobs\Currencies\SyncPriceCurrencies;
use App\Store\Models\Contracts\Price;

class PriceObserver implements ShouldHandleEventsAfterCommit
{
    public function created(Price $price): void
    {
        if ($price->currency->default) {
            SyncPriceCurrencies::dispatch($price);
        }
    }

    public function updated(Price $price): void
    {
        if ($price->currency->default) {
            SyncPriceCurrencies::dispatch($price);
        }
    }
}
