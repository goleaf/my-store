<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Contracts;

class AssociateUser extends AbstractAction
{
    /**
     * Execute the action
     *
     * @param  string  $policy
     */
    public function execute(Contracts\Cart $cart, Customer $customer, $policy = 'merge'): self
    {
        /** @var Cart $customerCart */
        if ($policy == 'merge') {
            $customerCart = Cart::query()
                ->where('customer_id', $customer->getKey())
                ->active()
                ->unMerged()
                ->latest()
                ->first();

            if ($customerCart) {
                app(MergeCart::class)->execute($cart, $customerCart);
            }
        }

        if ($policy == 'override') {
            $customerCart = Cart::query()
                ->where('customer_id', $customer->getKey())
                ->active()
                ->unMerged()
                ->latest()
                ->first();

            if ($customerCart && $customerCart->id != $cart->id) {
                $customerCart->update([
                    'merged_id' => $customerCart->id,
                ]);
            }
        }

        $cart->update([
            'customer_id' => $customer->getKey(),
        ]);

        return $this;
    }
}
