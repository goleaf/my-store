<?php

namespace App\Store\Observers;

use Illuminate\Database\Eloquent\Relations\Relation;
use App\Store\Base\Purchasable;
use App\Store\Exceptions\NonPurchasableItemException;
use App\Store\Models\Contracts\OrderLine as OrderLineContract;
use App\Store\Models\OrderLine as OrderLine;

class OrderLineObserver
{
    /**
     * Handle the OrderLine "creating" event.
     */
    public function creating(OrderLineContract $orderLine): void
    {
        /** @var OrderLine $orderLine */
        $purchasableModel = class_exists($orderLine->purchasable_type) ?
            $orderLine->purchasable_type :
            Relation::getMorphedModel($orderLine->purchasable_type);

        if (! $purchasableModel || ! in_array(Purchasable::class, class_implements($purchasableModel, true))) {
            throw new NonPurchasableItemException($purchasableModel);
        }
    }

    /**
     * Handle the OrderLine "updated" event.
     */
    public function updating(OrderLineContract $orderLine): void
    {
        $purchasableModel = class_exists($orderLine->purchasable_type) ?
            $orderLine->purchasable_type :
            Relation::getMorphedModel($orderLine->purchasable_type);

        if (! $purchasableModel || ! in_array(Purchasable::class, class_implements($purchasableModel, true))) {
            throw new NonPurchasableItemException($purchasableModel);
        }
    }
}
