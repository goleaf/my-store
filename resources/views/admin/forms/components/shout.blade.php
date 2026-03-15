@php
    $content = $content ?? '';
    $type = $type ?? 'info';
    $colorClasses = match ($type) {
        'danger' => 'fi-alert-danger',
        'warning' => 'fi-alert-warning',
        'success' => 'fi-alert-success',
        default => 'fi-alert-info',
    };
@endphp
<div class="rounded-xl fi-alert {{ $colorClasses }} p-4">
    <div class="fi-alert-message">{{ $content }}</div>
</div>
