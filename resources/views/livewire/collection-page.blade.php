<section>
    <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        {{-- Collection name (attribute_data from Filament) --}}
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ $this->collection->translateAttribute('name') }}
        </h1>

        {{-- Collection description (attribute_data) --}}
        @if($this->collection->translateAttribute('description'))
            <div class="max-w-3xl mt-4 text-gray-600 dark:text-gray-400">
                {!! $this->collection->translateAttribute('description') !!}
            </div>
        @endif

        {{-- Collection thumbnail (from Filament Manage Collection Media) --}}
        @if($this->collection->thumbnail)
            <div class="mt-6 overflow-hidden rounded-xl max-w-2xl aspect-video">
                <img src="{{ $this->collection->thumbnail->getUrl('large') }}"
                     alt="{{ $this->collection->translateAttribute('name') }}"
                     class="object-cover w-full h-full" />
            </div>
        @endif

        {{-- Child collections (from Filament collection tree) --}}
        @if($this->collection->children && $this->collection->children->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sub-collections</h2>
                <ul class="flex flex-wrap gap-3 mt-3">
                    @foreach($this->collection->children as $child)
                        @if($child->defaultUrl)
                            <li>
                                <a href="{{ route('collection.view', $child->defaultUrl->slug) }}"
                                   class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                                   wire:navigate>
                                    {{ $child->translateAttribute('name') }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Collection type & group (from Filament) --}}
        <div class="flex flex-wrap gap-2 mt-4 text-sm text-gray-500 dark:text-gray-400">
            @if($this->collection->group)
                <span>Group: {{ $this->collection->group->name }}</span>
            @endif
            @if($this->collection->type)
                <span>Type: {{ $this->collection->type }}</span>
            @endif
        </div>

        <h2 class="mt-8 text-xl font-semibold text-gray-900 dark:text-white">Products</h2>
        <div class="grid grid-cols-1 gap-8 mt-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($this->collection->products as $product)
                <x-product-card :product="$product" :is-wishlisted="in_array($product->id, $this->wishlistProductIds, true)" />
            @empty
                <p class="col-span-full text-gray-500 dark:text-gray-400">No products in this collection.</p>
            @endforelse
        </div>
    </div>
</section>
