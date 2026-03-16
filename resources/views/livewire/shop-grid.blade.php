<div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="w-full lg:w-1/4 space-y-8">
            <!-- Categories -->
            <div>
                <h3 class="text-lg font-bold mb-4">Categories</h3>
                <div class="space-y-2">
                    @foreach($allCategories as $category)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="categories" value="{{ $category->id }}" class="rounded text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-700">{{ $category->translateAttribute('name') }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Brands -->
            <div>
                <h3 class="text-lg font-bold mb-4">Brands</h3>
                <div class="space-y-2">
                    @foreach($allBrands as $brand)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="brands" value="{{ $brand->id }}" class="rounded text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-700">{{ $brand->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Price Range -->
            <div>
                <h3 class="text-lg font-bold mb-4">Price Range</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="w-1/2">
                            <label class="text-xs text-gray-500">Min</label>
                            <input type="number" wire:model.blur="minPrice" placeholder="0" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>
                        <div class="w-1/2">
                            <label class="text-xs text-gray-500">Max</label>
                            <input type="number" wire:model.blur="maxPrice" placeholder="{{ $maxPriceValue }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating -->
            <div>
                <h3 class="text-lg font-bold mb-4">Rating</h3>
                <div class="space-y-2">
                    @foreach([5, 4, 3, 2, 1] as $star)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="ratings" value="{{ $star }}" class="rounded text-green-600 focus:ring-green-500">
                            <div class="flex items-center text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 fill-current {{ $i < $star ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-700">& Up</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Reset Filters -->
            <button wire:click="resetFilters" class="w-full py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md transition duration-150">
                Reset Filters
            </button>
        </aside>

        <!-- Main Content -->
        <main class="w-full lg:w-3/4">
            <!-- Top Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-gray-50 p-4 rounded-lg">
                <div class="text-sm text-gray-500">
                    Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">Show:</label>
                        <select wire:model.live="perPage" class="border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">Sort by:</label>
                        <select wire:model.live="sort" class="border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            <option value="featured">Featured</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="date">Release Date</option>
                            <option value="rating">Avg. Rating</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <x-product-card :product="$product" :is-wishlisted="in_array($product->id, $this->wishlistProductIds, true)" :key="'product-'.$product->id" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </main>
    </div>
</div>
