@props([
    'categories',
])

@if ($categories->isNotEmpty())
    <section class="space-y-8">
        <div class="flex items-center justify-between gap-4">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-600">Browse Faster</p>
                <h2 class="text-2xl font-bold text-slate-900">Shop by Categories</h2>
            </div>

            <a href="{{ route('shop.view') }}" wire:navigate class="text-sm font-semibold text-slate-700 transition hover:text-emerald-600">
                View all categories
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6 xl:grid-cols-8">
            @forelse ($categories as $category)
                @php
                    $collectionName = $category->collection->translateAttribute('name');
                    $categoryTitle = $category->title ?: $collectionName;
                    $imageUrl = $category->image
                        ? Storage::disk('public')->url($category->image)
                        : $category->collection->thumbnail?->getUrl('medium');
                @endphp

                <a
                    href="{{ route('collection.view', $category->collection->defaultUrl->slug) }}"
                    wire:navigate
                    wire:key="featured-category-{{ $category->id }}"
                    class="group flex flex-col items-center rounded-[1.5rem] border border-slate-100 bg-white p-4 text-center shadow-sm transition hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl"
                >
                    <div class="mb-3 aspect-square w-full overflow-hidden rounded-full bg-slate-50 ring-1 ring-slate-100">
                        @if ($imageUrl)
                            <img
                                src="{{ $imageUrl }}"
                                alt="{{ $categoryTitle }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-50 via-white to-amber-50 text-2xl font-black uppercase tracking-[0.12em] text-emerald-500">
                                {{ \Illuminate\Support\Str::upper(mb_substr($collectionName ?: $categoryTitle, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    @if ($category->title)
                        <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-600">
                            {{ $category->title }}
                        </span>
                    @endif

                    <span class="mt-1 text-sm font-semibold text-slate-800 transition group-hover:text-emerald-600">
                        {{ $collectionName }}
                    </span>

                    <span class="mt-1 text-xs text-slate-500">
                        Shop collection
                    </span>
                </a>
            @empty
            @endforelse
        </div>
    </section>
@endif
