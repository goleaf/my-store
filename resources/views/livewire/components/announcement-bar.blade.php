<div x-data="{ show: @entangle('isVisible') }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-full" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-full">
    @if($announcement)
        <div class="relative bg-gray-900 text-white py-2 px-4 text-center text-sm font-medium z-50" 
             style="background-color: {{ $announcement->bg_color ?? '#111827' }}; color: {{ $announcement->text_color ?? '#ffffff' }};">
            <div class="container mx-auto flex items-center justify-center relative">
                <p class="pr-8">
                    {!! nl2br(e($announcement->message)) !!}
                </p>
                
                <button type="button" wire:click="dismiss" class="absolute right-0 p-1 hover:opacity-75 transition-opacity" aria-label="Dismiss">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>
