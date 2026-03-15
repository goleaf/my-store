<span {{ $attributes }}>
    @if($comparePrice())
        <span class="text-gray-400 line-through dark:text-gray-500">{{ $comparePrice()->compare_price->formatted() }}</span>
        <span class="ml-2 font-semibold text-indigo-600 dark:text-indigo-400">{{ $price?->price->formatted() }}</span>
    @else
        {{ $price?->price->formatted() }}
    @endif
</span>
