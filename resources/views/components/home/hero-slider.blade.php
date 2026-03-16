@props([
    'heroes',
])

@if ($heroes->isNotEmpty())
    <section class="relative overflow-hidden rounded-[2rem] bg-linear-to-br from-lime-50 via-white to-amber-50 shadow-[0_24px_80px_-40px_rgba(21,128,61,0.45)] ring-1 ring-lime-100">
        <div
            x-data="{ active: 0, count: {{ $heroes->count() }} }"
            x-init="if (count > 1) { setInterval(() => active = (active + 1) % count, 6000) }"
            class="relative min-h-[460px] overflow-hidden sm:min-h-[540px] lg:min-h-[620px]"
        >
            @foreach ($heroes as $index => $hero)
                <div
                    x-show="active === {{ $index }}"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-400"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-10"
                    wire:key="home-hero-slide-{{ $hero->id }}"
                    class="absolute inset-0"
                >
                    <div class="absolute inset-y-0 right-0 hidden w-1/2 rounded-l-full bg-linear-to-br from-lime-200/60 via-emerald-100/40 to-transparent blur-3xl lg:block"></div>
                    <div class="grid h-full items-center gap-10 px-6 py-10 sm:px-10 lg:grid-cols-[1.15fr_0.85fr] lg:px-16 xl:px-20">
                        <div class="relative z-10 space-y-6">
                            @if ($hero->translate('subtitle'))
                                <span class="inline-flex items-center rounded-full bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700 ring-1 ring-emerald-100 backdrop-blur">
                                    {{ $hero->translate('subtitle') }}
                                </span>
                            @endif

                            <div class="space-y-4">
                                <h1 class="max-w-3xl text-4xl font-black tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                                    {{ $hero->translate('title') }}
                                </h1>

                                @if ($hero->translate('description'))
                                    <p class="max-w-2xl text-base leading-8 text-slate-600 sm:text-lg">
                                        {{ $hero->translate('description') }}
                                    </p>
                                @endif
                            </div>

                            @if ($hero->translate('button_text'))
                                <x-link-or-text
                                    :href="$hero->translate('link')"
                                    class="inline-flex items-center gap-3 rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-emerald-600"
                                >
                                    <span>{{ $hero->translate('button_text') }}</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                                    </svg>
                                </x-link-or-text>
                            @endif
                        </div>

                        <div class="relative z-10 hidden h-full items-center justify-center lg:flex">
                            <div class="absolute inset-0 scale-90 rounded-full bg-white/70 blur-3xl"></div>
                            @if ($hero->image)
                                <img
                                    src="{{ Storage::disk('public')->url($hero->image) }}"
                                    alt="{{ $hero->translate('title') }}"
                                    class="relative max-h-[520px] w-full object-contain drop-shadow-[0_25px_45px_rgba(15,23,42,0.18)]"
                                >
                            @else
                                <div class="relative flex aspect-square w-full max-w-md items-center justify-center rounded-full border border-dashed border-emerald-200 bg-white/80 text-center text-sm font-medium text-slate-500">
                                    {{ $hero->translate('title') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($heroes->count() > 1)
                <div class="absolute inset-x-0 bottom-6 z-20 flex justify-center gap-2">
                    @foreach ($heroes as $index => $hero)
                        <button
                            type="button"
                            @click="active = {{ $index }}"
                            wire:key="home-hero-dot-{{ $hero->id }}"
                            :class="active === {{ $index }} ? 'w-10 bg-slate-900' : 'w-3 bg-slate-300/80 hover:bg-slate-400'"
                            class="h-3 rounded-full transition-all"
                            aria-label="Show slide {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
