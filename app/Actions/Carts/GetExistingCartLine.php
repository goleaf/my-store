<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\Base\Purchasable;
use App\Models\Contracts\CartLine;
use App\Utils\Arr;
use App\Models\Contracts\Cart;

class GetExistingCartLine extends AbstractAction
{
    /**
     * Execute the action
     */
    public function execute(
        Cart $cart,
        Purchasable $purchasable,
        array $meta = []
    ): ?CartLine {
        /** @var \App\Models\Cart $cart */

        // Get all possible cart lines
        $lines = $cart->lines()
            ->wherePurchasableType(
                $purchasable->getMorphClass()
            )->wherePurchasableId($purchasable->id)
            ->get();

        return $lines->first(function ($line) use ($meta) {
            $diff = Arr::diff($line->meta, $meta);

            return empty($diff->new) && empty($diff->edited) && empty($diff->removed);
        });
    }
}
