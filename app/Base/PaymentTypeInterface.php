<?php

namespace App\Base;

use App\Base\DataTransferObjects\PaymentAuthorize;
use App\Base\DataTransferObjects\PaymentCapture;
use App\Base\DataTransferObjects\PaymentRefund;
use App\Models\Contracts\Cart;
use App\Models\Contracts\Order;
use App\Models\Contracts\Transaction;

interface PaymentTypeInterface
{
    /**
     * Set the cart.
     *
     * @param  \App\Models\Cart  $order
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
