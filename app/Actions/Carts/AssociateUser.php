<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\Base\StoreUser;
use App\Models\Cart;
use App\Models\Contracts\Cart as CartContract;

class AssociateUser extends AbstractAction
{
    /**
     * Execute the action
     *
     * @param  string  $policy
     */
    public function execute(CartContract $cart, StoreUser $user, $policy = 'merge'): self
    {
        /** @var Cart $userCart */
        if ($policy == 'merge') {
            $userCart = Cart::whereUserId($user->getKey())->active()->unMerged()->latest()->first();
            if ($userCart) {
                app(MergeCart::class)->execute($cart, $userCart);
            }
        }

        if ($policy == 'override') {
            $userCart = Cart::whereUserId($user->getKey())->active()->unMerged()->latest()->first();
            if ($userCart && $userCart->id != $cart->id) {
                $userCart->update([
                    'merged_id' => $userCart->id,
                ]);
            }
        }

        $cart->update([
            'user_id' => $user->getKey(),
            'customer_id' => $user->latestCustomer()?->getKey(),
        ]);

        return $this;
    }
}
