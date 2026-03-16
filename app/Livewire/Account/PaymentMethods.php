<?php

namespace App\Livewire\Account;

use App\Base\Enums\SavedPaymentMethodType;
use App\Http\Requests\Account\StorePaymentMethodRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Payment Methods')]
class PaymentMethods extends Component
{
    public bool $showAddMethodForm = false;

    public array $method = [
        'type' => SavedPaymentMethodType::Card->value,
        'cardholder_name' => '',
        'card_number' => '',
        'expiry_month' => '',
        'expiry_year' => '',
        'paypal_email' => '',
        'payoneer_account_id' => '',
        'is_default' => false,
    ];

    public function mount(): void
    {
        $this->resetMethodForm();
    }

    public function addMethod(): void
    {
        $paymentType = SavedPaymentMethodType::from($this->method['type']);
        $request = (new StorePaymentMethodRequest)->forPaymentType($paymentType);
        $validated = $request->validatePayload([
            'method' => $this->method,
        ]);
        $methodData = $validated['method'];

        $user = Auth::user();
        $payload = [
            'type' => $paymentType->value,
            'is_default' => (bool) $methodData['is_default'],
        ];

        if ($paymentType === SavedPaymentMethodType::Card) {
            $number = preg_replace('/\D+/', '', $methodData['card_number']) ?? '';

            $payload = array_merge($payload, [
                'brand' => $this->detectBrand($number),
                'last_four' => substr($number, -4),
                'expiry_month' => (int) $methodData['expiry_month'],
                'expiry_year' => (int) $methodData['expiry_year'],
            ]);
        }

        if ($paymentType === SavedPaymentMethodType::Paypal) {
            $payload['paypal_email'] = $methodData['paypal_email'];
        }

        if ($paymentType === SavedPaymentMethodType::Payoneer) {
            $payload['payoneer_account_id'] = $methodData['payoneer_account_id'];
        }

        if (! $user->paymentMethods()->exists()) {
            $payload['is_default'] = true;
        }

        if ($payload['is_default']) {
            $user->paymentMethods()->update(['is_default' => false]);
        }

        $user->paymentMethods()->create($payload);

        $this->resetMethodForm();
        session()->flash('status', 'Payment method added successfully.');
    }

    public function deleteMethod(int $id): void
    {
        $methods = Auth::user()->paymentMethods();
        $method = $methods->findOrFail($id);
        $wasDefault = (bool) $method->is_default;

        $method->delete();

        if ($wasDefault) {
            $methods->latest('id')->first()?->update(['is_default' => true]);
        }

        session()->flash('status', 'Payment method deleted successfully.');
    }

    public function setDefault(int $id): void
    {
        $methods = Auth::user()->paymentMethods();

        if (! $methods->whereKey($id)->exists()) {
            return;
        }

        $methods->update(['is_default' => false]);
        $methods->whereKey($id)->update(['is_default' => true]);

        session()->flash('status', 'Default payment method updated successfully.');
    }

    public function render(): View
    {
        return view('livewire.account.payment-methods', [
            'paymentMethods' => Auth::user()->paymentMethods()
                ->orderByDesc('is_default')
                ->latest()
                ->get(),
        ]);
    }

    public function getPaymentMethodTypeOptionsProperty(): array
    {
        return SavedPaymentMethodType::options();
    }

    public function getSelectedPaymentMethodTypeProperty(): SavedPaymentMethodType
    {
        return SavedPaymentMethodType::from($this->method['type'] ?? SavedPaymentMethodType::Card->value);
    }

    private function resetMethodForm(): void
    {
        $this->showAddMethodForm = false;
        $this->method = [
            'type' => SavedPaymentMethodType::Card->value,
            'cardholder_name' => '',
            'card_number' => '',
            'expiry_month' => '',
            'expiry_year' => '',
            'paypal_email' => '',
            'payoneer_account_id' => '',
            'is_default' => false,
        ];
    }

    private function detectBrand(string $number): string
    {
        if (preg_match('/^4/', $number)) {
            return 'Visa';
        }

        if (preg_match('/^(5[1-5]|2[2-7])/', $number)) {
            return 'Mastercard';
        }

        if (preg_match('/^3[47]/', $number)) {
            return 'American Express';
        }

        if (preg_match('/^6(?:011|5)/', $number)) {
            return 'Discover';
        }

        return 'Card';
    }
}
