<?php

namespace App\Store\PaymentTypes;

use App\Store\Base\DataTransferObjects\PaymentAuthorize;
use App\Store\Base\DataTransferObjects\PaymentCapture;
use App\Store\Base\DataTransferObjects\PaymentRefund;
use App\Store\Events\PaymentAttemptEvent;
use App\Store\Models\Contracts\Transaction as TransactionContract;

class OfflinePayment extends AbstractPayment
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): ?PaymentAuthorize
    {
        if (! $this->order) {
            if (! $this->order = $this->cart->draftOrder()->first()) {
                $this->order = $this->cart->createOrder();
            }
        }
        $orderMeta = array_merge(
            (array) $this->order->meta,
            $this->data['meta'] ?? []
        );

        $status = $this->data['authorized'] ?? null;

        $this->order->update([
            'status' => $status ?? ($this->config['authorized'] ?? null),
            'meta' => $orderMeta,
            'placed_at' => now(),
        ]);

        $response = new PaymentAuthorize(
            success: true,
            orderId: $this->order->id,
            paymentType: 'offline',
        );

        PaymentAttemptEvent::dispatch($response);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function refund(TransactionContract $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        return new PaymentRefund(true);
    }

    /**
     * {@inheritDoc}
     */
    public function capture(TransactionContract $transaction, $amount = 0): PaymentCapture
    {
        return new PaymentCapture(true);
    }
}
