<?php

namespace App\Store\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface Language
{
    /**
     * Return the URLs relationship
     */
    public function urls(): HasMany;
}
