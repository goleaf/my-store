<?php

namespace App\Store\Base\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Store\Models\Cart;
use App\Store\Models\Customer;
use App\Store\Models\Order;

trait LunarUser
{
    public function customers(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(Customer::class, "{$prefix}customer_user");
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function latestCustomer(): ?Customer
    {
        return $this->customers()->orderBy('created_at', 'desc')->orderBy('id', 'desc')->first();
    }

    /**
     * Return the user orders relationship.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
