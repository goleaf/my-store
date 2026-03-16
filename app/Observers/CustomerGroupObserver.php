<?php

namespace App\Observers;

use App\Models\CustomerGroup;
use App\Models\Contracts;

class CustomerGroupObserver
{
    /**
     * Handle the CustomerGroup "created" event.
     *
     * @return void
     */
    public function created(Contracts\CustomerGroup $customerGroup)
    {
        $this->ensureOnlyOneDefault($customerGroup);
    }

    /**
     * Handle the CustomerGroup "updated" event.
     *
     * @return void
     */
    public function updated(Contracts\CustomerGroup $customerGroup)
    {
        $this->ensureOnlyOneDefault($customerGroup);
    }

    /**
     * Handle the CustomerGroup "deleted" event.
     *
     * @return void
     */
    public function deleted(Contracts\CustomerGroup $customerGroup)
    {
        //
    }

    /**
     * Handle the CustomerGroup "forceDeleted" event.
     *
     * @return void
     */
    public function forceDeleted(Contracts\CustomerGroup $customerGroup)
    {
        //
    }

    /**
     * Ensures that only one default CustomerGroup exists.
     *
     * @param  \App\Models\Contracts\CustomerGroup  $savedCustomerGroup  The customer group that was just saved.
     */
    protected function ensureOnlyOneDefault(Contracts\CustomerGroup $savedCustomerGroup): void
    {
        // Wrap here so we avoid a query if it's not been set to default.
        if ($savedCustomerGroup->default) {
            CustomerGroup::withoutEvents(function () use ($savedCustomerGroup) {
                CustomerGroup::whereDefault(true)->where('id', '!=', $savedCustomerGroup->id)->update([
                    'default' => false,
                ]);
            });
        }
    }
}
