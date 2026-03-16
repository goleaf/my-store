<?php

namespace App\Store\Actions\Carts;

use App\Store\Actions\AbstractAction;
use App\Store\Base\Purchasable;
use App\Store\Models\Cart;
use App\Store\Models\Contracts\Cart as CartContract;
use App\Store\Models\Contracts\CartLine as CartLineContract;
use App\Store\Utils\Arr;

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
