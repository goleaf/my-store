<?php

namespace App\Observers;

use App\Models\Contracts\Transaction;

class TransactionObserver
{
    /**
     * Handle the \App\Models\Transaction "created" event.
     *
     * @return void
     */
    public function created(Transaction $transaction)
    {
        /** @var \App\Models\Transaction $transaction */
        activity()
            ->causedBy(auth()->user())
            ->performedOn($transaction->order)
            ->event($transaction->type)
            ->withProperties([
                'amount' => $transaction->amount->value,
                'type' => $transaction->type,
                'status' => $transaction->status,
                'card_type' => $transaction->card_type,
                'last_four' => $transaction->last_four,
                'reference' => $transaction->reference,
                'notes' => $transaction->notes ?: '',
            ])->log('created');
    }
}
