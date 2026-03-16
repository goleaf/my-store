<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" type="button" class="flex items-center gap-1 text-sm font-medium transition hover:opacity-75">
        {{ $this->currency->code }}
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 z-50 mt-2 w-32 origin-top-right rounded-md bg-white p-1 shadow-lg ring-1 ring-black ring-opacity-5">
        @foreach($this->currencies as $currency)
            <button wire:click="setCurrency({{ $currency->id }})" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                {{ $currency->code }} ({{ $currency->name }})
            </button>
        @endforeach
    </div>
</div>
