<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
            @include('livewire.account.sidebar')
        </aside>

        <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payment Methods</h3>
                    <button wire:click="$set('showAddCardForm', true)" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                        Add Payment Method
                    </button>
                </div>

                @if (session('status'))
                    <div class="mx-4 mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                        <p class="text-sm text-green-700">{{ session('status') }}</p>
                    </div>
                @endif

                @if ($showAddCardForm)
                    <div class="p-6 border-t border-gray-200">
                        <form wire:submit="addCard" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cardholder Name</label>
                                <input type="text" wire:model="newCard.name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                @error('newCard.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card Number</label>
                                <input type="text" wire:model="newCard.number" placeholder="0000 0000 0000 0000" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                @error('newCard.number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Expiry (MM/YY)</label>
                                    <input type="text" wire:model="newCard.expiry" placeholder="MM/YY" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('newCard.expiry') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">CVV</label>
                                    <input type="text" wire:model="newCard.cvv" placeholder="000" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('newCard.cvv') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" wire:click="$set('showAddCardForm', false)" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                                    Save Card
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="p-6 border-t border-gray-200">
                    <ul class="divide-y divide-gray-200">
                        @forelse ($paymentMethods as $card)
                            <li class="py-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <x-lucide-credit-card class="h-8 w-8 text-gray-400" />
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $card['brand'] }} ending in {{ $card['last4'] }}</p>
                                        <p class="text-sm text-gray-500">Expires {{ $card['exp_month'] }}/{{ $card['exp_year'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <button wire:click="deleteCard('{{ $card['id'] }}')" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
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
