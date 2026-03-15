@props(['product'])

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
<a class="block group"
   href="{{ route('product.view', $productUrl->slug) }}"
   wire:navigate
>
    <div class="overflow-hidden rounded-lg aspect-w-1 aspect-h-1">
        @if ($product->thumbnail)
            <img class="object-cover transition-transform duration-300 group-hover:scale-105"
                 src="{{ $product->thumbnail->getUrl('medium') }}"
                 alt="{{ $product->translateAttribute('name') }}" />
        @endif
    </div>

    @if($product->brand)
        <p class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400">{{ $product->brand->name }}</p>
    @endif

    <strong class="mt-1 text-sm font-medium block">
        {{ $product->translateAttribute('name') }}
    </strong>

    @if($product->tags && $product->tags->isNotEmpty())
        <div class="flex flex-wrap gap-1 mt-1">
            @foreach($product->tags->take(3) as $tag)
                <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700">{{ $tag->name }}</span>
            @endforeach
        </div>
    @endif

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        <span class="sr-only">Price</span>
        <x-product-price :product="$product" :show-compare="$hasCompare" />
    </p>

    @if($variant && $variant->sku)
        <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">SKU: {{ $variant->sku }}</p>
    @endif
</a>
@endif
