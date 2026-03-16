<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Contracts\Customer;

interface StoreUser
{
    public function customers(): BelongsToMany;

    public function carts(): HasMany;

    public function latestCustomer(): ?Customer;

    public function orders(): HasMany;
}
