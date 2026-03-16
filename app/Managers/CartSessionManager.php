<?php

namespace App\Managers;

use Illuminate\Auth\AuthManager;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;
use App\Base\CartSessionInterface;
use App\Facades\ShippingManifest;
use App\Models\Cart;
use App\Models\Channel;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Contracts;

class CartSessionManager implements CartSessionInterface
{
    public function __construct(
        protected SessionManager $sessionManager,
        protected AuthManager $authManager,
        protected Contracts\Channel $channel,
        protected Contracts\Currency $currency,
        public ?Contracts\Cart $cart = null,
    ) {
        //
    }

    public function allowsMultipleOrdersPerCart(): bool
    {
        return config('store.cart_session.allow_multiple_orders_per_cart', false);
    }

    /**
     * {@inheritDoc}
     */
    public function current(bool $estimateShipping = false, bool $calculate = true): ?Cart
    {
        return $this->fetchOrCreate(
            config('store.cart_session.auto_create', false),
            estimateShipping: $estimateShipping,
            calculate: $calculate,
        );
    }

    /**
     * Set the criteria to use when estimating shipping costs.
     *
     * @return $this
     */
    public function estimateShippingUsing(array $meta): self
    {
        $this->sessionManager->put('shipping_estimate_meta', $meta);

        return $this;
    }

    /**
     * Return the shipping estimate meta.
     */
    public function getShippingEstimateMeta(): array
    {
        return $this->sessionManager->get('shipping_estimate_meta', []);
    }

    /**
     * {@inheritDoc}
     */
    public function forget(?bool $delete = null): void
    {
        $delete = is_null($delete) ? config('store.cart_session.delete_on_forget', true) : $delete;

        if ($delete) {
            Cart::destroy(
                $this->sessionManager->get(
                    $this->getSessionKey()
                )
            );
        }

        $this->cart = null;

        $this->sessionManager->forget('shipping_estimate_meta');
        $this->sessionManager->forget(
            $this->getSessionKey()
        );

    }

    /**
     * {@inheritDoc}
     */
    public function manager(): ?Cart
    {
        if (! $this->cart?->exists) {
            $this->fetchOrCreate(create: true);
        }

        return $this->cart;
    }

    /**
     * {@inheritDoc}
     */
    public function associate(Contracts\Cart $cart, Customer $customer, $policy): void
    {
        /** @var Cart $cart */
        $this->use(
            $cart->associate($customer, $policy)
        );
    }

    /**
     * Set the cart to be used for the session.
     */
    public function use(Contracts\Cart $cart): Contracts\Cart
    {
        /** @var Cart $cart */
        $this->sessionManager->put(
            $this->getSessionKey(),
            $cart->id
        );

        return $this->cart = $cart;
    }

    /**
     * Fetches a cart and optionally creates one if it doesn't exist.
     */
    protected function fetchOrCreate(bool $create = false, bool $estimateShipping = false, bool $calculate = true): ?Cart
    {
        $cartId = $this->sessionManager->get(
            $this->getSessionKey()
        );

        if (! $cartId && $user = $this->authManager->user()) {
            $cartId = $user->carts()->active()->first()?->id;
        }

        if (! $cartId) {
            return $create ? $this->cart = $this->createNewCart() : null;
        }

        $cart = $this->cart?->exists ? $this->cart : Cart::with(
            config('store.cart.eager_load', [])
        )->find($cartId);

        if (app()->environment('testing') && $cart instanceof Collection) {
            dd('CartSessionManager::fetchOrCreate - cart is a collection: ' . $cart->count());
        }

        if (! $cart) {
            return $create ? $this->createNewCart() : null;
        }

        if ($cart->hasCompletedOrders() && ! $this->allowsMultipleOrdersPerCart()) {
            return $this->createNewCart();
        }

        $this->cart = $cart;

        if (app()->environment('testing') && !($this->cart instanceof Cart) && !is_null($this->cart)) {
            logger('CartSessionManager::fetchOrCreate - cart is not an instance of Cart: ' . get_class($this->cart));
        }

        if ($calculate) {
            $this->cart->calculate();
        }

        if ($estimateShipping) {
            $this->estimateShipping();
        }

        return $this->use($this->cart);
    }

    public function estimateShipping(): void
    {
        if (! $this->cart?->exists) {
            return;
        }

        // Some shipping drivers might require sub-totals to be present
        // before they can estimate a shipping cost, doing this in the driver
        // itself can lead to infinite loops, so we calculate before.
        $this->cart->calculate();
        $this->cart->getEstimatedShipping(
            $this->getShippingEstimateMeta(),
            setOverride: true
        );
        $this->cart->calculate(force: true);
    }

    /**
     * Get the cart session key.
     */
    public function getSessionKey(): string
    {
        return config('store.cart_session.session_key');
    }

    /**
     * Set the current channel.
     */
    public function setChannel(Contracts\Channel $channel): void
    {
        /** @var Channel $channel */
        $this->channel = $channel;

        if ($this->current() && $this->current()->channel_id != $channel->id) {
            $this->cart->update([
                'channel_id' => $channel->id,
            ]);
        }
    }

    /**
     * Set the current currency.
     */
    public function setCurrency(Contracts\Currency $currency): void
    {
        /** @var Currency $currency */
        $this->currency = $currency;

        if ($this->current() && $this->current()->currency_id != $currency->id) {
            $this->cart->update([
                'currency_id' => $currency->id,
            ]);
        }
    }

    /**
     * Return the current currency.
     */
    public function getCurrency(): Contracts\Currency
    {
        $currency = $this->currency?->exists ? $this->currency : Currency::modelClass()::getDefault();

        if (!$currency) {
            $currency = new Currency([
                'code' => 'USD',
                'name' => 'Default',
                'decimal_places' => 2,
                'exchange_rate' => 1,
            ]);
        }

        return $currency;
    }

    /**
     * Return the current channel.
     */
    public function getChannel(): Contracts\Channel
    {
        $channel = $this->channel?->exists ? $this->channel : Channel::modelClass()::getDefault();

        if (!$channel) {
            $channel = new Channel([
                'handle' => 'webstore',
                'name' => 'Web Store',
            ]);
        }

        return $channel;
    }

    /**
     * Return available shipping options for the current cart.
     */
    public function getShippingOptions(): Collection
    {
        return ShippingManifest::getOptions(
            $this->current()
        );
    }

    /**
     * Create an order from a cart instance.
     */
    public function createOrder(bool $forget = true): Order
    {
        $order = $this->manager()->createOrder(
            allowMultipleOrders: $this->allowsMultipleOrdersPerCart()
        );

        if ($forget) {
            $this->forget();
        }

        return $order;
    }

    /**
     * Create a new cart instance.
     */
    protected function createNewCart(): Contracts\Cart
    {
        $customer = is_store_user($this->authManager->user()) ? $this->authManager->user() : null;

        $cart = Cart::create([
            'currency_id' => $this->getCurrency()->id,
            'channel_id' => $this->getChannel()->id,
            'customer_id' => optional($customer)->id,
        ]);

        return $this->use($cart);
    }

    public function __call($method, $args)
    {
        if (! $this->cart?->exists) {
            $this->fetchOrCreate(create: true, calculate: false);
        }

        return $this->cart->{$method}(...$args);
    }
}
