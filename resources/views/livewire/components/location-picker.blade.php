<div x-data="{ open: false }" @keydown.escape.window="open = false">
    <!-- Trigger Button -->
    <button @click="open = true" class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 transition-colors py-2 px-3 rounded-md hover:bg-gray-50 border border-transparent hover:border-gray-100">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <div class="text-left hidden lg:block">
            <span class="block text-[10px] uppercase tracking-wider text-gray-400 leading-none mb-1">Deliver to</span>
            <span class="block text-sm font-bold leading-none truncate max-w-[120px]">
                {{ $selectedZone ? $selectedZone->name : 'Select Location' }}
            </span>
        </div>
    </button>

    <!-- Modal Portal (standard in modern Livewire/Alpine apps) -->
    <div x-show="open" 
         class="fixed inset-0 z-[1000] overflow-y-auto" 
         x-cloak
         @click.away="open = false">
        
        <!-- Backdrop -->
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>

        <!-- Modal Content -->
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                
                <div class="px-6 py-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-extrabold text-gray-900">Choose Delivery Location</h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="relative mb-6">
                        <input type="text" 
                               wire:model.live="search"
                               placeholder="Search for your state or region..."
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        <div class="absolute left-3 top-3.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Location List -->
                    <div class="max-h-80 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                        @forelse($zones as $zone)
                            <button 
                                wire:click="selectZone({{ $zone->id }})"
                                @click="open = false"
                                class="w-full flex items-center justify-between p-4 rounded-xl border transition-all text-left group {{ $selectedZoneId === $zone->id ? 'border-primary-500 bg-primary-50' : 'border-gray-100 hover:border-primary-300 hover:bg-gray-50' }}"
                            >
                                <div>
                                    <span class="block font-bold text-gray-900 group-hover:text-primary-700">{{ $zone->name }}</span>
                                    <span class="block text-xs text-gray-500 mt-1">Min. order: {{ number_format($zone->min_order, 2) }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm font-semibold text-gray-700 mr-3">
                                        @if($zone->delivery_fee > 0)
                                            + {{ number_format($zone->delivery_fee, 2) }} fee
                                        @else
                                            <span class="text-green-600">Free delivery</span>
                                        @endif
                                    </span>
                                    @if($selectedZoneId === $zone->id)
                                        <svg class="w-6 h-6 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                            </button>
                        @empty
                            <div class="py-12 text-center text-gray-500">
                                No locations found matching your search.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-100">
                        <button wire:click="clearLocation" @click="open = false" class="text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">
                            Clear Current Selection
                        </button>
                        <button @click="open = false" class="bg-gray-900 text-white px-6 py-2 rounded-xl font-bold hover:bg-black transition-colors">
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
