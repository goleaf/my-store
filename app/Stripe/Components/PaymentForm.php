<?php

namespace App\Stripe\Components;

use Livewire\Component;
use App\Models\Contracts\Cart;
use App\Stripe\Facades;
use Stripe;

class PaymentForm extends Component
{
    /**
     * The instance of the order.
     */
    public Cart $cart;

    /**
     * The return URL on a successful transaction
     *
     * @var string
     */
    public $returnUrl;

    /**
     * The policy for handling payments.
     *
     * @var string
     */
    public $policy;

    /**
     * {@inheritDoc}
     */
    protected $listeners = [
        'cardDetailsSubmitted',
    ];

    /**
     * {@inheritDoc}
     */
    public function mount()
    {
        Stripe\Stripe::setApiKey(config('services.stripe.key'));
        $this->policy = config('stripe.policy', 'capture');
    }

    /**
     * Return the client secret for Payment Intent
     *
     * @return void
     */
    public function getClientSecretProperty()
    {
        $intent = Facades\Stripe::createIntent($this->cart);

        return $intent->client_secret;
    }

    /**
     * Return the carts billing address.
     *
     * @return void
     */
    public function getBillingProperty()
    {
        return $this->cart->billingAddress;
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return view('store::stripe.components.payment-form');
    }
}
