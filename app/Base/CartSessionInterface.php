<?php

namespace App\Base;

use App\Models\Customer;
use App\Models\Contracts\Cart;
use App\Models\Contracts\Channel;
use App\Models\Contracts\Currency;

interface CartSessionInterface
{
    /**
     * Return the current cart.
     *
     * @return \App\Models\Cart|null
     */
    public function current();

    /**
     * Forget the current cart session.
     *
     * @return void
     */
    public function forget();

    /**
     * Associate a cart to a user.
     *
     * @param  string  $policy
     * @return void
     */
    public function associate(Cart $cart, Customer $user, $policy);

    /**
     * Use the given cart and set to the session.
     *
     * @return void
     */
    public function use(Cart $cart);

    /**
     * Return the session key for carts.
     *
     * @return string
     */
    public function getSessionKey();

    /**
     * Set the cart session channel.
     *
     * @return self
     */
    public function setChannel(Channel $channel);

    /**
     * Set the cart session currency.
     *
     * @return self
     */
    public function setCurrency(Currency $currency);

    /**
     * Return the current currency.
     */
    public function getCurrency(): Currency;

    /**
     * Return the current channel.
     */
    public function getChannel(): Channel;
}
