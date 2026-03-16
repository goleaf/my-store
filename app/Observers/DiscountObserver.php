<?php

namespace App\Observers;

use App\Models\Contracts\Discount;

class DiscountObserver
{
    /**
     * Handle the Discount "deleting" event.
     */
    public function deleting(Discount $discount): void
    {
        $discount->brands()->detach();
        $discount->collections()->detach();
        $discount->customerGroups()->detach();
        $discount->customers()->detach();
        $discount->discountables()->delete();
    }
}
