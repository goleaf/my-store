@php
    use App\Base\Enums\SavedPaymentMethodType;
@endphp

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
            @include('livewire.account.sidebar')
        </aside>

        <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payment Methods</h3>
                    <button wire:click="$set('showAddMethodForm', true)" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                        Add Payment Method
                    </button>
                </div>

                @if (session('status'))
                    <div class="mx-4 mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                        <p class="text-sm text-green-700">{{ session('status') }}</p>
                    </div>
                @endif

                @if ($showAddMethodForm)
                    <div class="p-6 border-t border-gray-200">
                        <form wire:submit="addMethod" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Method Type</label>
                                <select wire:model.live="method.type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @foreach ($this->paymentMethodTypeOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('method.type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            @if ($this->selectedPaymentMethodType === SavedPaymentMethodType::Card)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cardholder Name</label>
                                    <input type="text" wire:model="method.cardholder_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('method.cardholder_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Card Number</label>
                                    <input type="text" wire:model="method.card_number" placeholder="0000 0000 0000 0000" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('method.card_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Expiry Month</label>
                                        <input type="number" min="1" max="12" wire:model="method.expiry_month" placeholder="12" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                        @error('method.expiry_month') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Expiry Year</label>
                                        <input type="number" min="{{ now()->year }}" wire:model="method.expiry_year" placeholder="{{ now()->year + 1 }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                        @error('method.expiry_year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            @if ($this->selectedPaymentMethodType === SavedPaymentMethodType::Paypal)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">PayPal Email</label>
                                    <input type="email" wire:model="method.paypal_email" placeholder="paypal@example.com" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('method.paypal_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            @if ($this->selectedPaymentMethodType === SavedPaymentMethodType::Payoneer)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Payoneer Account ID</label>
                                    <input type="text" wire:model="method.payoneer_account_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('method.payoneer_account_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="payment_default" wire:model="method.is_default" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <label for="payment_default" class="text-sm font-medium text-gray-700">Set as default payment method</label>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" wire:click="$set('showAddMethodForm', false)" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                                    Save Method
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="p-6 border-t border-gray-200">
                    <ul class="divide-y divide-gray-200">
                        @forelse ($paymentMethods as $paymentMethod)
                            <li class="py-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <x-lucide-credit-card class="h-8 w-8 text-gray-400" />
                                    <div class="ml-4">
                                        @if ($paymentMethod->type === SavedPaymentMethodType::Card)
                                            <p class="text-sm font-medium text-gray-900">{{ $paymentMethod->brand ?: 'Card' }} ending in {{ $paymentMethod->last_four }}</p>
                                            <p class="text-sm text-gray-500">Expires {{ str_pad((string) $paymentMethod->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $paymentMethod->expiry_year }}</p>
                                        @elseif ($paymentMethod->type === SavedPaymentMethodType::Paypal)
                                            <p class="text-sm font-medium text-gray-900">{{ SavedPaymentMethodType::Paypal->label() }}</p>
                                            <p class="text-sm text-gray-500">{{ $paymentMethod->paypal_email }}</p>
                                        @else
                                            <p class="text-sm font-medium text-gray-900">{{ SavedPaymentMethodType::Payoneer->label() }}</p>
                                            <p class="text-sm text-gray-500">{{ $paymentMethod->payoneer_account_id }}</p>
                                        @endif
                                        @if ($paymentMethod->is_default)
                                            <span class="mt-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    @if (! $paymentMethod->is_default)
                                        <button wire:click="setDefault({{ $paymentMethod->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Set Default</button>
                                    @endif
                                    <button wire:click="deleteMethod({{ $paymentMethod->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                                </div>
                            </li>
                        @empty
                            <li class="py-10 text-center text-gray-500">
                                No payment methods saved.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
