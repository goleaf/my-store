<?php

namespace App\Observers;

use App\Models\Address;
use App\Models\Contracts;

class AddressObserver
{
    /**
     * Handle the Address "creating" event.
     *
     * @return void
     */
    public function creating(Contracts\Address $address)
    {
        $this->ensureOnlyOneDefaultShipping($address);
        $this->ensureOnlyOneDefaultBilling($address);
    }

    /**
     * Handle the Address "updating" event.
     *
     * @return void
     */
    public function updating(Contracts\Address $address)
    {
        $this->ensureOnlyOneDefaultShipping($address);
        $this->ensureOnlyOneDefaultBilling($address);
    }

    /**
     * Ensures that only one default shipping address exists.
     *
     * @param  \App\Models\Contracts\Address  $address  The address that will be saved.
     */
    protected function ensureOnlyOneDefaultShipping(Contracts\Address $address): void
    {
        /** @var Address $address */
        if ($address->shipping_default) {
            $address = Address::query()
                ->whereCustomerId($address->customer_id)
                ->where('id', '!=', $address->id)
                ->whereShippingDefault(true)
                ->first();

            if ($address) {
                $address->shipping_default = false;
                $address->saveQuietly();
            }
        }
    }

    /**
     * Ensures that only one default billing address exists.
     *
     * @param  \App\Models\Contracts\Address  $address  The address that will be saved.
     */
    protected function ensureOnlyOneDefaultBilling(Contracts\Address $address): void
    {
        /** @var Address $address */
        if ($address->billing_default) {
            $address = Address::query()
                ->whereCustomerId($address->customer_id)
                ->where('id', '!=', $address->id)
                ->whereBillingDefault(true)
                ->first();

            if ($address) {
                $address->billing_default = false;
                $address->saveQuietly();
            }
        }
    }
}
