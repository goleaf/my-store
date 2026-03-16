@props([
    'banners',
    'variant' => 'middle',
])

@php
    $gridClasses = match ($variant) {
        'top' => 'grid grid-cols-1 gap-6 md:grid-cols-2',
        'bottom' => 'grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4',
        default => 'grid grid-cols-1 gap-6 md:grid-cols-3',
    };

    $heightClasses = match ($variant) {
        'top' => 'min-h-[260px]',
        'bottom' => 'min-h-[220px]',
        default => 'min-h-[220px]',
    };
@endphp

@if ($banners->isNotEmpty())
    <section class="{{ $gridClasses }}">
        @foreach ($banners as $banner)
            <article
                wire:key="home-banner-{{ $variant }}-{{ $banner->id }}"
                class="group relative isolate overflow-hidden rounded-[1.75rem] border border-slate-100 bg-slate-100 shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
            >
                <div class="absolute inset-0">
                    @if ($banner->image)
                        <img
                            src="{{ Storage::disk('public')->url($banner->image) }}"
                            alt="{{ $banner->title }}"
                            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                        >
                    @else
                        <div class="h-full w-full bg-linear-to-br from-lime-100 via-white to-amber-100"></div>
                    @endif
                    <div class="absolute inset-0 bg-linear-to-r from-white via-white/88 to-white/25"></div>
                </div>

                <div class="relative flex {{ $heightClasses }} flex-col justify-center gap-3 p-6 lg:p-8">
                    @if ($banner->subtitle)
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-600">
                            {{ $banner->subtitle }}
                        </p>
                    @endif

                    <h3 class="max-w-xs text-2xl font-bold text-slate-900">
                        {{ $banner->title }}
                    </h3>

                    @if ($banner->link)
                        <x-link-or-text
                            :href="$banner->link"
                            class="inline-flex w-max items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600"
                        >
                            <span>{{ $variant === 'bottom' ? 'Explore' : 'Shop now' }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </x-link-or-text>
                    @endif
                </div>
            </article>
        @endforeach
    </section>
@endif
