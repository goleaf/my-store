<?php

namespace App\Store\Base;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Store\Models\Contracts\Customer;

interface StoreUser
{
    public function customers(): BelongsToMany;

    public function carts(): HasMany;

    public function latestCustomer(): ?Customer;

    public function orders(): HasMany;
}
