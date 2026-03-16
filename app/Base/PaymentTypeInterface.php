<?php

namespace App\Store\Base;

use App\Store\Base\DataTransferObjects\PaymentAuthorize;
use App\Store\Base\DataTransferObjects\PaymentCapture;
use App\Store\Base\DataTransferObjects\PaymentRefund;
use App\Store\Models\Contracts\Cart;
use App\Store\Models\Contracts\Order;
use App\Store\Models\Contracts\Transaction;

interface PaymentTypeInterface
{
    /**
     * Set the cart.
     *
     * @param  \App\Store\Models\Cart  $order
     */
    public function cart(Cart $cart): self;

    /**
     * Set the order.
     */
    public function order(Order $order): self;

    /**
     * Set any data the provider might need.
     */
    public function withData(array $data): self;

    /**
     * Set any configuration on the driver.
     */
    public function setConfig(array $config): self;

    /**
     * Authorize the payment.
     */
    public function authorize(): ?PaymentAuthorize;

    /**
     * Refund a transaction for a given amount.
     *
     * @param  null|string  $notes
     */
    public function refund(Transaction $transaction, int $amount, $notes = null): PaymentRefund;

    /**
     * Capture an amount for a transaction.
     *
     * @param  int  $amount
     */
    public function capture(Transaction $transaction, $amount = 0): PaymentCapture;
}
