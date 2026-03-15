<?php

namespace App\Store\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface CollectionGroup
{
    /**
     * Return the collection group collections relationship.
     */
    public function collections(): HasMany;
}
