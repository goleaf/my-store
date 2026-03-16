<?php

namespace App\Store\Observers;

use App\Store\Models\Contracts\Discount as DiscountContract;

class DiscountObserver
{
    /**
     * Handle the Discount "deleting" event.
     */
    public function deleting(DiscountContract $discount): void
    {
        $discount->brands()->detach();
        $discount->collections()->detach();
        $discount->customerGroups()->detach();
        $discount->customers()->detach();
        $discount->discountables()->delete();
        $discount->users()->detach();
    }
}
