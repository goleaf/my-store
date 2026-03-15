<?php

namespace App\Store\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface State
{
    /**
     * Return the country relationship.
     */
    public function country(): BelongsTo;
}
