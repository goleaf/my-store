<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
            @include('livewire.account.sidebar')
        </aside>

        <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Addresses</h3>
                    <button wire:click="$set('showAddressForm', true)" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                        Add New Address
                    </button>
                </div>

                @if (session('status'))
                    <div class="mx-4 mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                        <p class="text-sm text-green-700">{{ session('status') }}</p>
                    </div>
                @endif

                @if ($showAddressForm)
                    <div class="p-6 border-t border-gray-200">
                        <form wire:submit="saveAddress" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" wire:model="address.title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('address.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                                    <input type="text" wire:model="address.company_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('address.company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" wire:model="address.first_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('address.first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" wire:model="address.last_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('address.last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tax Identifier</label>
                                <input type="text" wire:model="address.tax_identifier" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                @error('address.tax_identifier') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Address Line 1</label>
                                <input type="text" wire:model="address.line_one" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                @error('address.line_one') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Address Line 2 (Optional)</label>
                                    <input type="text" wire:model="address.line_two" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Address Line 3 (Optional)</label>
                                    <input type="text" wire:model="address.line_three" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" wire:model="address.city" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('address.city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">State / Province</label>
                                    <input type="text" wire:model="address.state" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ZIP / Postcode</label>
                                    <input type="text" wire:model="address.postcode" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('address.postcode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="address.contact_email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('address.contact_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" wire:model="address.contact_phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    @error('address.contact_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Country</label>
                                <select wire:model="address.country_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                                    <option value="">Select a country</option>
                                    @foreach ($countries as $countryId => $countryName)
                                        <option value="{{ $countryId }}">{{ $countryName }}</option>
                                    @endforeach
                                </select>
                                @error('address.country_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="shipping_default" wire:model="address.shipping_default" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <label for="shipping_default" class="text-sm font-medium text-gray-700">Set as default address</label>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" wire:click="$set('showAddressForm', false)" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                                    {{ $editingAddressId ? 'Update Address' : 'Save Address' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 border-t border-gray-200">
                    @forelse ($addresses as $addr)
                        <div class="border rounded-lg p-4 relative">
                            <div class="flex justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $addr->title ?: 'Address' }}</p>
                                    <h4 class="font-semibold">{{ trim($addr->first_name.' '.$addr->last_name) }}</h4>
                                </div>
                                <div class="space-x-2 text-right">
                                    <button wire:click="editAddress({{ $addr->id }})" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                    <button wire:click="deleteAddress({{ $addr->id }})" class="text-red-600 hover:text-red-800 text-sm" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">
                                {{ $addr->line_one }}@if($addr->line_two), {{ $addr->line_two }}@endif @if($addr->line_three), {{ $addr->line_three }}@endif<br>
                                {{ $addr->city }}, {{ $addr->state }} {{ $addr->postcode }}<br>
                                {{ $addr->country?->name }}<br>
                                {{ $addr->contact_phone }}
                            </p>
                            <div class="mt-3 flex items-center gap-2">
                                @if($addr->shipping_default || $addr->billing_default)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Default
                                    </span>
                                @else
                                    <button wire:click="setDefault({{ $addr->id }})" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        Make Default
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-10 text-gray-500">
                            No addresses found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
