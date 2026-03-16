<section>
    <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">
            Search Results
            @if (isset($term))
                for <u>{{ $term }}</u>
            @endif
        </h1>

        @forelse ($this->results as $result)
            @if ($loop->first)
                <div class="grid grid-cols-1 gap-8 mt-8 sm:grid-cols-2 lg:grid-cols-4">
            @endif
                    <x-product-card :product="$result" :is-wishlisted="in_array($result->id, $this->wishlistProductIds, true)" />
            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="mt-8 rounded-xl border border-dashed border-gray-300 px-6 py-12 text-center text-gray-500">
                No products matched your search.
            </div>
        @endforelse

        @if ($this->results->hasPages())
            <div class="mt-8">
                {{ $this->results->links() }}
            </div>
        @endif
    </div>
</section>
