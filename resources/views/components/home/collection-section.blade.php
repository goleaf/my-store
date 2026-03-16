@props([
    'section',
    'wishlistProductIds' => [],
])

@php
    use App\Base\Enums\HomeSectionType;

    $products = $section->collection?->products ?? collect();
    $collectionUrl = $section->collection?->defaultUrl?->slug
        ? route('collection.view', $section->collection->defaultUrl->slug)
        : null;
@endphp

@if ($section->collection)
    <section wire:key="home-section-{{ $section->id }}" class="space-y-8">
        <div class="flex items-center justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-2xl font-bold text-slate-900">{{ $section->title }}</h2>
                @if ($section->subtitle)
                    <p class="text-sm text-slate-500">{{ $section->subtitle }}</p>
                @endif
            </div>

            <x-link-or-text
                :href="$collectionUrl"
                navigate
                class="text-sm font-semibold text-slate-700 transition hover:text-emerald-600"
            >
                View more
            </x-link-or-text>
        </div>

        @if ($section->type === HomeSectionType::ProductGrid)
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-5">
                @forelse ($products->take(10) as $product)
                    <x-product-card
                        :product="$product"
                        :is-wishlisted="in_array($product->id, $wishlistProductIds, true)"
                        :key="'product-grid-section-'.$section->id.'-product-'.$product->id"
                    />
                @empty
                    <div class="col-span-full rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center text-sm text-slate-500">
                        Products for this section will appear here once the collection is populated.
                    </div>
                @endforelse
            </div>
        @elseif ($section->type === HomeSectionType::SidebarGrid)
            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="overflow-hidden rounded-[2rem] bg-linear-to-br from-slate-950 via-slate-900 to-emerald-900 p-8 text-white shadow-[0_24px_80px_-40px_rgba(15,23,42,0.7)]">
                    <div class="space-y-4">
                        @if ($section->subtitle)
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-300">
                                {{ $section->subtitle }}
                            </p>
                        @endif

                        <h3 class="text-3xl font-black">{{ $section->title }}</h3>
                        <p class="max-w-md text-sm leading-7 text-slate-200">
                            Pick from the best-selling items in this collection and add them to your cart in a few taps.
                        </p>

                        <x-link-or-text
                            :href="$collectionUrl"
                            navigate
                            class="inline-flex w-max items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-emerald-300"
                        >
                            <span>Shop all</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </x-link-or-text>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3">
                    @forelse ($products->take(6) as $product)
                        <x-product-card
                            :product="$product"
                            :is-wishlisted="in_array($product->id, $wishlistProductIds, true)"
                            :key="'sidebar-grid-section-'.$section->id.'-product-'.$product->id"
                        />
                    @empty
                        <div class="col-span-full rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center text-sm text-slate-500">
                            Products for this section will appear here once the collection is populated.
                        </div>
                    @endforelse
                </div>
            </div>
        @elseif ($section->type === HomeSectionType::FeaturedItems)
            <div class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-sm lg:p-8">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div class="space-y-2">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-600">
                            Featured selection
                        </p>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $section->title }}</h3>
                    </div>

                    <x-link-or-text
                        :href="$collectionUrl"
                        navigate
                        class="text-sm font-semibold text-slate-700 transition hover:text-emerald-600"
                    >
                        Browse collection
                    </x-link-or-text>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                    @forelse ($products->take(4) as $product)
                        <x-product-card
                            :product="$product"
                            :is-wishlisted="in_array($product->id, $wishlistProductIds, true)"
                            :key="'featured-items-section-'.$section->id.'-product-'.$product->id"
                        />
                    @empty
                        <div class="col-span-full rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center text-sm text-slate-500">
                            Featured items will show here after products are assigned to this collection.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </section>
@endif
