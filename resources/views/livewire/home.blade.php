<div>
    <main class="max-w-screen-xl px-4 py-8 mx-auto space-y-12 sm:px-6 lg:px-8">
        {{-- Hero Slider Section --}}
        @if($this->heroes->count() > 0)
            <section class="relative overflow-hidden rounded-2xl bg-gray-100 dark:bg-gray-800">
                <div x-data="{ active: 0, count: {{ $this->heroes->count() }} }" class="relative h-[400px] sm:h-[500px] lg:h-[600px]">
                    @foreach($this->heroes as $index => $hero)
                        <div x-show="active === {{ $index }}"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 translate-x-full"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-500"
                             x-transition:leave-start="opacity-100 translate-x-0"
                             x-transition:leave-end="opacity-0 -translate-x-full"
                             class="absolute inset-0 flex items-center p-8 sm:p-16"
                        >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center w-full">
                                <div class="space-y-6">
                                    @if($hero->subtitle)
                                        <span class="inline-block px-3 py-1 text-xs font-semibold tracking-wider text-green-700 uppercase bg-green-100 rounded-full">
                                            {{ $hero->subtitle }}
                                        </span>
                                    @endif
                                    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-5xl lg:text-6xl">
                                        {{ $hero->title }}
                                    </h1>
                                    @if($hero->description)
                                        <p class="text-xl text-gray-600 dark:text-gray-300 max-w-lg">
                                            {{ $hero->description }}
                                        </p>
                                    @endif
                                    @if($hero->button_text)
                                        <a href="{{ $hero->link ?? '#' }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 transition-colors">
                                            {{ $hero->button_text }}
                                            <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                                @if($hero->image)
                                    <div class="hidden lg:block relative">
                                        <img src="{{ Storage::disk('public')->url($hero->image) }}" alt="{{ $hero->title }}" class="w-full h-auto object-contain max-h-[500px]">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($this->heroes->count() > 1)
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2">
                            @foreach($this->heroes as $index => $hero)
                                <button @click="active = {{ $index }}" :class="active === {{ $index }} ? 'bg-green-600' : 'bg-gray-300'" class="w-3 h-3 rounded-full transition-colors focus:outline-none"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- Featured Categories Section --}}
        @if($this->featuredCategories->count() > 0)
            <section>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Shop by Categories</h2>
                    <a href="#" class="text-sm font-medium text-green-600 hover:text-green-500">View All</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach($this->featuredCategories as $category)
                        @if($category->collection && $category->collection->defaultUrl)
                            <a href="{{ route('collection.view', $category->collection->defaultUrl->slug) }}" class="group block text-center p-4 border border-gray-100 rounded-xl hover:shadow-lg hover:border-green-200 transition-all dark:border-gray-700" wire:navigate>
                                <div class="mb-3 overflow-hidden rounded-full aspect-square bg-gray-50 dark:bg-gray-800">
                                    @if($category->image)
                                        <img src="{{ Storage::disk('public')->url($category->image) }}" alt="{{ $category->title ?? $category->collection->translateAttribute('name') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                    @elseif($category->collection->thumbnail)
                                        <img src="{{ $category->collection->thumbnail->getUrl('medium') }}" alt="{{ $category->collection->translateAttribute('name') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                    @endif
                                </div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-green-600">
                                    {{ $category->title ?? $category->collection->translateAttribute('name') }}
                                </h3>
                            </a>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Top Banners Section --}}
        @php $topBanners = $this->banners->where('type', 'top'); @endphp
        @if($topBanners->count() > 0)
            <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($topBanners as $banner)
                    <div class="relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 min-h-[250px] group">
                        <div class="absolute inset-0">
                            @if($banner->image)
                                <img src="{{ Storage::disk('public')->url($banner->image) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif
                        </div>
                        <div class="relative p-8 h-full flex flex-col justify-center">
                            @if($banner->subtitle)
                                <p class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-2">{{ $banner->subtitle }}</p>
                            @endif
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 max-w-xs">{{ $banner->title }}</h3>
                            @if($banner->link)
                                <a href="{{ $banner->link }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-md hover:bg-gray-800 transition-colors w-max">
                                    Shop Now
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </section>
        @endif

        {{-- Home Sections (Product Grids) --}}
        @foreach($this->sections as $section)
            @if($section->collection)
                <section>
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $section->title }}</h2>
                            @if($section->subtitle)
                                <p class="text-sm text-gray-500 mt-1">{{ $section->subtitle }}</p>
                            @endif
                        </div>
                        @if($section->collection->defaultUrl)
                            <a href="{{ route('collection.view', $section->collection->defaultUrl->slug) }}" class="text-sm font-medium text-green-600 hover:text-green-500">View More</a>
                        @endif
                    </div>

                    @if($section->type === 'product_grid')
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                            @foreach($section->collection->products->take(10) as $product)
                                <x-product-card :product="$product" :key="'section-'.$section->id.'-product-'.$product->id" />
                            @endforeach
                        </div>
                    @elseif($section->type === 'sidebar_grid')
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                            <div class="lg:col-span-1">
                                <div class="bg-gray-900 rounded-2xl p-8 h-full min-h-[350px] flex flex-col justify-end text-white relative overflow-hidden">
                                    <div class="relative z-10">
                                        <h3 class="text-3xl font-bold mb-4">{{ $section->title }}</h3>
                                        <p class="text-gray-300 mb-6">{{ $section->subtitle }}</p>
                                        @if($section->collection->defaultUrl)
                                            <a href="{{ route('collection.view', $section->collection->defaultUrl->slug) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 transition-colors">
                                                Shop All
                                            </a>
                                        @endif
                                    </div>
                                    {{-- Optional background decoration or image --}}
                                </div>
                            </div>
                            <div class="lg:col-span-3">
                                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                    @foreach($section->collection->products->take(6) as $product)
                                        <x-product-card :product="$product" :key="'section-'.$section->id.'-product-'.$product->id" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </section>
            @endif
        @endforeach

        {{-- Middle Banners Section --}}
        @php $middleBanners = $this->banners->where('type', 'middle'); @endphp
        @if($middleBanners->count() > 0)
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($middleBanners as $banner)
                    <div class="relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 h-[200px] group">
                         <div class="absolute inset-0">
                            @if($banner->image)
                                <img src="{{ Storage::disk('public')->url($banner->image) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif
                        </div>
                        <div class="relative p-6 h-full flex flex-col justify-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 max-w-[150px]">{{ $banner->title }}</h3>
                            @if($banner->subtitle)
                                <p class="text-sm text-gray-600 mb-4">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->link)
                                <a href="{{ $banner->link }}" class="text-sm font-bold text-gray-900 hover:text-green-600 transition-colors">
                                    Shop Now
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </section>
        @endif

        {{-- Service Features Section --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 py-12 border-t border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center space-x-4">
                <div class="text-green-600">
                    <x-lucide-clock class="w-8 h-8" />
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">10 minute grocery now</h4>
                    <p class="text-xs text-gray-500">Get your order delivered to your doorstep at the earliest from FreshCart pickup stores near you.</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-green-600">
                    <x-lucide-gift class="w-8 h-8" />
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Best Prices & Offers</h4>
                    <p class="text-xs text-gray-500">Cheaper prices than your local supermarket, great cashback offers to top it off. Get best pricess & offers.</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-green-600">
                    <x-lucide-package class="w-8 h-8" />
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Wide Assortment</h4>
                    <p class="text-xs text-gray-500">Choose from 5000+ products across food, personal care, household, bakery, veg and fruit and other categories.</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-green-600">
                    <x-lucide-rotate-ccw class="w-8 h-8" />
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Easy Returns</h4>
                    <p class="text-xs text-gray-500">Not satisfied with a product? Return it at the doorstep & get a refund within hours. No questions asked policy.</p>
                </div>
            </div>
        </section>
    </main>
</div>
