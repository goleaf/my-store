<?php

namespace App\Observers;

use App\Models\Contracts\Customer;

class CustomerObserver
{
    /**
     * Handle the Discount "deleting" event.
     *
     * @return void
     */
    public function deleting(Customer $customer)
    {
        $customer->customerGroups()->detach();
        $customer->discounts()->detach();
    }
}
