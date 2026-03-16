<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\Base\Purchasable;
use App\Models\Cart;
use App\Models\Contracts\Cart as CartContract;
use App\Models\Contracts\CartLine as CartLineContract;
use App\Utils\Arr;

class GetExistingCartLine extends AbstractAction
{
    /**
     * Execute the action
     */
    public function execute(
        CartContract $cart,
        Purchasable $purchasable,
        array $meta = []
    ): ?CartLineContract {
        /** @var Cart $cart */

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
