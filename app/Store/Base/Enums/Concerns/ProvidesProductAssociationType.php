<?php

namespace App\Store\Base\Enums\Concerns;

use App\Store\Base\Enums\ProductAssociation;

/**
 * @mixin ProductAssociation
 */
interface ProvidesProductAssociationType
{
    public function label(): string;
}
