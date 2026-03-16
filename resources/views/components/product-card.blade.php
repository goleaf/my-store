@props([
    'product',
    'isWishlisted' => false,
])

@php
    $variant = $product->variants->first();
    $hasCompare = $variant && $variant->basePrices->first()?->compare_price;
    $productUrl = $product->defaultUrl;
@endphp

@if(!$productUrl)
    <div class="block p-4 rounded-lg border border-gray-200 dark:border-gray-600 opacity-75">
        <strong class="text-sm font-medium">{{ $product->translateAttribute('name') }}</strong>
        <p class="mt-1 text-xs text-gray-500">No URL</p>
    </div>
@else
<div class="group relative bg-white border border-gray-100 rounded-xl hover:shadow-lg transition-all duration-300 h-full flex flex-col overflow-hidden">
    {{-- Image Container --}}
    <div class="relative overflow-hidden aspect-square bg-gray-50">
        @if ($product->thumbnail)
            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                 src="{{ $product->thumbnail->getUrl('medium') }}"
                 alt="{{ $product->translateAttribute('name') }}" />
        @endif

        {{-- Badge (New/Sale) --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1">
            @if($hasCompare)
                <span class="px-2 py-1 text-[10px] font-bold text-white bg-red-500 rounded uppercase">Sale</span>
            @endif
        </div>

        {{-- Overlay Actions --}}
        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
            <a href="{{ route('product.view', $productUrl->slug) }}" class="p-2 bg-white rounded-full shadow-md hover:bg-green-600 hover:text-white transition-colors" title="Quick View" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </a>
            <button
                type="button"
                wire:click="toggleWishlist({{ $product->id }})"
                class="p-2 bg-white rounded-full shadow-md transition-colors {{ $isWishlisted ? 'text-red-500 hover:bg-red-500 hover:text-white' : 'hover:bg-red-500 hover:text-white' }}"
                title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-4 flex flex-col flex-1">
        @if($product->brand)
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $product->brand->name }}</p>
        @endif

        <h3 class="text-sm font-semibold text-gray-800 mb-1 group-hover:text-green-600 transition-colors line-clamp-2">
            <a href="{{ route('product.view', $productUrl->slug) }}" wire:navigate>
                {{ $product->translateAttribute('name') }}
            </a>
        </h3>

        {{-- Rating --}}
        <div class="flex items-center gap-1 mb-3">
            <div class="flex text-yellow-400">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-3 h-3 fill-current {{ $i < floor($product->rating) ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
            </div>
            <span class="text-[10px] text-gray-400">({{ $product->total_reviews }})</span>
        </div>

        {{-- Price & Add --}}
        <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50">
            <div class="flex flex-col">
                <span class="text-sm font-bold text-gray-900">
                    <x-product-price :product="$product" />
                </span>
                @if($hasCompare)
                    <span class="text-[10px] text-gray-400 line-through">
                         {{ $variant->basePrices->first()->compare_price->formatted }}
                    </span>
                @endif
            </div>

            @if($variant)
                <button
                    wire:click="addToCart({{ $variant->id }})"
                    class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center justify-center shadow-sm shadow-green-200"
                    title="Add to Cart"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            @endif
        </div>
    </div>
</div>
@endif
