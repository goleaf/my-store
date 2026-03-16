<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Payment Methods')]
class PaymentMethods extends Component
{
    public bool $showAddCardForm = false;
    public array $newCard = [
        'name' => '',
        'number' => '',
        'expiry' => '',
        'cvv' => '',
    ];

    public function addCard(): void
    {
        $this->validate([
            'newCard.name' => 'required|string|max:255',
            'newCard.number' => 'required|string|min:16|max:19',
            'newCard.expiry' => 'required|string|regex:/^\d{2}\/\d{2}$/',
            'newCard.cvv' => 'required|string|min:3|max:4',
        ]);

        $customer = Auth::user()->latestCustomer();
        $meta = $customer->meta ?? [];
        $paymentMethods = $meta['payment_methods'] ?? [];

        $paymentMethods[] = [
            'id' => uniqid(),
            'brand' => 'Visa', // Hardcoded for demo
            'last4' => substr($this->newCard['number'], -4),
            'exp_month' => explode('/', $this->newCard['expiry'])[0],
            'exp_year' => explode('/', $this->newCard['expiry'])[1],
            'name' => $this->newCard['name'],
        ];

        $meta['payment_methods'] = $paymentMethods;
        $customer->update(['meta' => $meta]);

        $this->reset(['showAddCardForm', 'newCard']);
        session()->flash('status', 'Payment method added successfully.');
    }

    public function deleteCard(string $id): void
    {
        $customer = Auth::user()->latestCustomer();
        $meta = $customer->meta ?? [];
        $paymentMethods = $meta['payment_methods'] ?? [];

        $paymentMethods = array_filter($paymentMethods, fn($card) => $card['id'] !== $id);

        $meta['payment_methods'] = array_values($paymentMethods);
        $customer->update(['meta' => $meta]);

        session()->flash('status', 'Payment method deleted successfully.');
    }

    public function render()
    {
        $customer = Auth::user()->latestCustomer();
        $paymentMethods = $customer->meta['payment_methods'] ?? [];

        return view('livewire.account.payment-methods', [
            'paymentMethods' => $paymentMethods,
        ]);
    }
}
