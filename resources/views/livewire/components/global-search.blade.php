<div class="relative w-full max-w-2xl" x-data="{ open: @entangle('showDropdown') }" @click.away="open = false" @keydown.escape.window="open = false">
    <form wire:submit="search" class="relative flex items-center">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="term"
            placeholder="Search products, categories, SKU..."
            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
            @focus="if($wire.term.length >= 2) open = true"
        >
        <div class="absolute left-3 text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        
        <div wire:loading wire:target="term" class="absolute right-3">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    @if($showDropdown && count($results) > 0)
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute mt-2 w-full bg-white rounded-xl shadow-2xl border border-gray-100 z-[100] overflow-hidden">
            <div class="p-2">
                @foreach($results as $product)
                    <a href="{{ route('product.view', $product->defaultUrl->slug) }}" class="flex items-center p-3 hover:bg-gray-50 rounded-lg group transition-colors">
                        <div class="w-12 h-12 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden mr-4">
                            @if($product->thumbnail)
                                <img src="{{ $product->thumbnail->getUrl('small') }}" alt="{{ $product->translateAttribute('name') }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                                {{ $product->translateAttribute('name') }}
                            </h4>
                            <div class="flex items-center mt-1">
                                <span class="text-xs text-gray-500 mr-2">{{ $product->variants->first()?->sku }}</span>
                                @if($product->variants->first()?->prices->first())
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $product->variants->first()->prices->first()->price->formatted }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div class="bg-gray-50 p-3 border-t border-gray-100 text-center">
                <button type="submit" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                    See all results for "{{ $term }}"
                </button>
            </div>
        </div>
    @elseif($showDropdown && count($results) === 0)
        <div x-show="open" class="absolute mt-2 w-full bg-white rounded-xl shadow-2xl border border-gray-100 z-[100] p-8 text-center">
            <div class="text-gray-400 mb-2">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-gray-600 font-medium">No results found for "{{ $term }}"</p>
            <p class="text-gray-400 text-sm mt-1">Try checking for typos or using broader terms.</p>
        </div>
    @endif
    </form>
</div>
