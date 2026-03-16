<?php

namespace App\Observers;

use App\Models\Contracts\Order;

class OrderObserver
{
    /**
     * Handle the \App\Models\Order "updating" event.
     *
     * @return void
     */
    public function updating(Order $order)
    {
        /** @var \App\Models\Order $order */
        if ($order->getOriginal('status') != $order->status) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($order)
                ->event('status-update')
                ->withProperties([
                    'new' => $order->status,
                    'previous' => $order->getOriginal('status'),
                ])->log('status-update');
        }
    }
}
