<section>
    <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Your Wishlist</h1>
                <p class="mt-2 text-sm text-gray-500">Save products here and come back to them anytime.</p>
            </div>
            <a href="{{ route('shop.view') }}" wire:navigate class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:border-primary-200 hover:text-primary-600">
                Continue Shopping
            </a>
        </div>

        @if($this->items->isNotEmpty())
            <div class="mt-8 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($this->items as $item)
                    @if($item->product)
                        <x-product-card :product="$item->product" :is-wishlisted="true" />
                    @endif
                @endforeach
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-dashed border-gray-300 px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="mt-4 text-lg font-semibold text-gray-900">Your wishlist is empty</h2>
                <p class="mt-2 text-sm text-gray-500">Browse the catalog and save products you want to revisit.</p>
                <a href="{{ route('shop.view') }}" wire:navigate class="mt-6 inline-flex items-center rounded-lg bg-primary-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-primary-700">
                    Explore Products
                </a>
            </div>
        @endif
    </div>
</section>
