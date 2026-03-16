<?php

namespace App\PaymentTypes;

use App\Base\DataTransferObjects\PaymentChecks;
use App\Base\PaymentTypeInterface;
use App\Models\Contracts\Transaction;
use App\Models\Contracts\Cart;
use App\Models\Contracts\Order;

abstract class AbstractPayment implements PaymentTypeInterface
{
    /**
     * The instance of the cart.
     */
    protected ?Cart $cart = null;

    /**
     * The instance of the order.
     */
    protected ?Order $order = null;

    /**
     * Any config for this payment provider.
     */
    protected array $config = [];

    /**
     * Data storage.
     */
    protected array $data = [];

    /**
     * {@inheritDoc}
     */
    public function cart(Cart $cart): self
    {
        /** @var \App\Models\Cart $cart */
        $this->cart = $cart;
        $this->order = null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function order(Order $order): self
    {
        /** @var \App\Models\Order $order */
        $this->order = $order;
        $this->cart = null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function getPaymentChecks(Transaction $transaction): PaymentChecks
    {
        return new PaymentChecks;
    }
}
