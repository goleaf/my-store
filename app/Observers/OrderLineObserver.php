<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Relations\Relation;
use App\Base\Purchasable;
use App\Exceptions\NonPurchasableItemException;
use App\Models\Contracts\OrderLine;

class OrderLineObserver
{
    /**
     * Handle the \App\Models\OrderLine "creating" event.
     */
    public function creating(OrderLine $orderLine): void
    {
        /** @var \App\Models\OrderLine $orderLine */
        $purchasableModel = class_exists($orderLine->purchasable_type) ?
            $orderLine->purchasable_type :
            Relation::getMorphedModel($orderLine->purchasable_type);

        if (! $purchasableModel || ! in_array(Purchasable::class, class_implements($purchasableModel, true))) {
            throw new NonPurchasableItemException($purchasableModel);
        }
    }

    /**
     * Handle the \App\Models\OrderLine "updated" event.
     */
    public function updating(OrderLine $orderLine): void
    {
        $purchasableModel = class_exists($orderLine->purchasable_type) ?
            $orderLine->purchasable_type :
            Relation::getMorphedModel($orderLine->purchasable_type);

        if (! $purchasableModel || ! in_array(Purchasable::class, class_implements($purchasableModel, true))) {
            throw new NonPurchasableItemException($purchasableModel);
        }
    }
}
