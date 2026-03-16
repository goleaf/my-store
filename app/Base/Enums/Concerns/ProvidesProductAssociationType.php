<?php

namespace App\Base\Enums\Concerns;

use App\Base\Enums\ProductAssociation;

/**
 * @mixin ProductAssociation
 */
interface ProvidesProductAssociationType
{
    public function label(): string;
}
