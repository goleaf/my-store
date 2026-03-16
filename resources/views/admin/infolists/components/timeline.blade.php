<section class="space-y-6">
    <x-filament::section.heading>
        {{ $getLabel() }}
    </x-filament::section.heading>

    @livewire(
        \App\Livewire\Components\ActivityLogFeed::class,
        [
            'subject' => $getRecord(),
        ],
        key('activity-log-feed-'.$getRecord()->getKey())
    )
</section>
