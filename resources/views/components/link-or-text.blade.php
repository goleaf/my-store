@props([
    'href' => null,
    'navigate' => false,
])

@if ($href)
    <a href="{{ $href }}" @if ($navigate) wire:navigate @endif {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <span {{ $attributes }}>
        {{ $slot }}
    </span>
@endif
