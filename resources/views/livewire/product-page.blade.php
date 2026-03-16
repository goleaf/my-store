@php
    use App\Base\Enums\ProductStatus;
    use App\Base\Enums\ProductVariantPurchasable;

    $fieldLabel = function (mixed $key): string {
        if ($key instanceof \BackedEnum) {
            $key = $key->value;
        }

        return str_replace('_', ' ', ucfirst((string) $key));
    };
@endphp

<section>
    <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        {{-- Status (from Filament product status) --}}
        @if($this->product->status !== ProductStatus::Published)
            <div class="p-3 mb-6 text-sm rounded-lg bg-amber-50 text-amber-800 dark:bg-amber-900/20 dark:text-amber-200">
                <span class="font-medium">Status:</span> {{ $this->product->status->label() }}
            </div>
        @endif

        <div class="grid items-start grid-cols-1 gap-8 md:grid-cols-2">
            {{-- Media (product + variant media from Filament) --}}
            <div class="grid grid-cols-2 gap-4 md:grid-cols-1">
                @if ($this->image)
                    <div class="aspect-w-1 aspect-h-1">
                        <img class="object-cover rounded-xl"
                             src="{{ $this->image->getUrl('large') }}"
                             alt="{{ $this->product->translateAttribute('name') }}" />
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @foreach ($this->images as $image)
                        <div class="aspect-w-1 aspect-h-1" wire:key="image_{{ $image->id }}">
                            <img loading="lazy"
                                 class="object-cover rounded-xl"
                                 src="{{ $image->getUrl('small') }}"
                                 alt="{{ $this->product->translateAttribute('name') }}" />
                        </div>
                    @endforeach
                    @if($this->variant->media && $this->variant->media->isNotEmpty())
                        @foreach($this->variant->media as $media)
                            <div class="aspect-w-1 aspect-h-1" wire:key="variant_media_{{ $media->id }}">
                                <img loading="lazy"
                                     class="object-cover rounded-xl"
                                     src="{{ $media->getUrl('small') }}"
                                     alt="{{ $this->product->translateAttribute('name') }}" />
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div>
                {{-- Brand, product type, tags (from Filament) --}}
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500">
                    @if($this->productBrandName)
                        <x-link-or-text :href="$this->productBrandUrl" :navigate="filled($this->productBrandUrl)" class="hover:underline">
                            {{ $this->productBrandName }}
                        </x-link-or-text>
                        @if($this->product->productType || $this->product->tags->isNotEmpty())<span>·</span>@endif
                    @endif
                    @if($this->product->productType)
                        <span>{{ $this->product->productType->name }}</span>
                        @if($this->product->tags->isNotEmpty())<span>·</span>@endif
                    @endif
                    @foreach($this->product->tags as $tag)
                        <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700">{{ $tag->name }}</span>
                    @endforeach
                </div>

                <div class="flex items-center justify-between mt-2">
                    <h1 class="text-xl font-bold">
                        {{ $this->product->translateAttribute('name') }}
                    </h1>
                    <x-product-price class="ml-4 font-medium" :variant="$this->variant" :show-compare="true" />
                </div>

                {{-- Identifiers (SKU, GTIN, EAN, MPN, tax_ref from Filament Manage Product Identifiers / Variant) --}}
                @if(count($this->variantIdentifiers) > 0)
                    <dl class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm text-gray-500">
                        @foreach($this->variantIdentifiers as $key => $value)
                            @if($value)
                                <div class="flex gap-1">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">{{ strtoupper($key) }}:</dt>
                                    <dd>{{ $value }}</dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                @endif

                {{-- Description (attribute_data from Filament) --}}
                <article class="mt-4 text-gray-700 dark:text-gray-300">
                    {!! $this->product->translateAttribute('description') ?? '—' !!}
                </article>

                {{-- Other product attribute_data (e.g. details) --}}
                @if(count($this->productAttributeData) > 0)
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Details</h3>
                        <dl class="mt-2 space-y-1 text-sm">
                            @foreach($this->productAttributeData as $key => $value)
                                @if(!in_array($key, ['name', 'description']) && $value)
                                    <div class="flex gap-2">
                                        <dt class="font-medium text-gray-600 dark:text-gray-400">{{ $fieldLabel($key) }}:</dt>
                                        <dd>{!! is_string($value) ? e($value) : $value !!}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                @endif

                {{-- Variant options (from Filament Product Options / Variants) --}}
                <form class="mt-4">
                    <div class="space-y-4">
                        @foreach ($this->productOptions as $option)
                            <fieldset>
                                <legend class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                    {{ $option['option']->translate('name') }}
                                </legend>
                                <div class="flex flex-wrap gap-2 mt-2 text-xs tracking-wide uppercase"
                                     x-data="{
                                         selectedOption: @entangle('selectedOptionValues').live,
                                         selectedValues: [],
                                     }"
                                     x-init="selectedValues = Object.values(selectedOption);
                                     $watch('selectedOption', value =>
                                         selectedValues = Object.values(selectedOption)
                                     )">
                                    @foreach ($option['values'] as $value)
                                        <button class="px-6 py-4 font-medium border rounded-lg focus:outline-none focus:ring"
                                                type="button"
                                                wire:click="$set('selectedOptionValues.{{ $option['option']->id }}', {{ $value->id }})"
                                                :class="{
                                                    'bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700': selectedValues.includes({{ $value->id }}),
                                                    'hover:bg-gray-100 dark:hover:bg-gray-700': !selectedValues.includes({{ $value->id }})
                                                }">
                                            {{ $value->translate('name') }}
                                        </button>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach
                    </div>

                    {{-- Inventory (stock, backorder, min/unit qty from Filament Manage Inventory) --}}
                    <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        @if($this->variant->stock > 0)
                            <span>In stock: {{ $this->variant->stock }}</span>
                        @else
                            <span class="text-amber-600 dark:text-amber-400">
                                @if($this->variant->backorder)
                                    Out of stock (backorder allowed)
                                @else
                                    Out of stock
                                @endif
                            </span>
                        @endif
                        @if($this->variant->min_quantity > 1 || $this->variant->quantity_increment > 1 || $this->variant->unit_quantity > 1)
                            <span class="ml-4">
                                Min: {{ $this->variant->min_quantity }}
                                @if($this->variant->quantity_increment > 1) · Increment: {{ $this->variant->quantity_increment }} @endif
                                @if($this->variant->unit_quantity > 1) · Unit qty: {{ $this->variant->unit_quantity }} @endif
                            </span>
                        @endif
                    </div>

                    {{-- Purchasable (from Filament) --}}
                    @if($this->variant->purchasable !== ProductVariantPurchasable::Always)
                        <p class="mt-2 text-sm text-amber-600 dark:text-amber-400">Availability: {{ $this->variant->purchasable->label() }}</p>
                    @endif

                    {{-- Dimensions & weight (from Filament Manage Variant Shipping) --}}
                    @if(count($this->variantDimensions) > 0)
                        <dl class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm text-gray-500">
                            @foreach($this->variantDimensions as $key => $value)
                                @if($value)
                                    <div><span class="font-medium text-gray-700 dark:text-gray-300">{{ $fieldLabel($key) }}:</span> {{ $value }}</div>
                                @endif
                            @endforeach
                        </dl>
                    @endif

                    {{-- Shippable (from Filament) --}}
                    <p class="mt-1 text-sm text-gray-500">{{ $this->variant->shippable ? 'Shippable' : 'Digital / No shipping' }}</p>

                    {{-- Tax class (from Filament Pricing) --}}
                    @if($this->variant->taxClass)
                        <p class="mt-1 text-sm text-gray-500">Tax: {{ $this->variant->taxClass->name }}</p>
                    @endif

                    {{-- Variant-specific attribute_data --}}
                    @if(count($this->variantAttributeData) > 0)
                        <dl class="mt-3 space-y-1 text-sm">
                            @foreach($this->variantAttributeData as $key => $value)
                                <div class="flex gap-2">
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">{{ $fieldLabel($key) }}:</dt>
                                    <dd>{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif

                    <div class="mt-8 flex max-w-md items-center gap-3">
                        <div class="max-w-xs flex-1">
                            <livewire:components.add-to-cart :purchasable="$this->variant" :wire:key="$this->variant->id" />
                        </div>
                        <button
                            type="button"
                            wire:click="toggleWishlist({{ $this->product->id }})"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Collections (from Filament Manage Product Collections) --}}
        @if($this->collectionLinks->isNotEmpty())
            <div class="pt-12 mt-12 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Collections</h2>
                <ul class="flex flex-wrap gap-2 mt-3">
                    @foreach($this->collectionLinks as $collection)
                        <li>
                            <x-link-or-text
                                :href="$collection['url']"
                                :navigate="filled($collection['url'])"
                                class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                            >
                                {{ $collection['name'] }}
                            </x-link-or-text>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Associated products (from Filament Manage Product Associations) --}}
        @if($this->associationCards->isNotEmpty())
            <div class="pt-12 mt-12 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Related products</h2>
                <div class="grid grid-cols-2 gap-4 mt-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($this->associationCards as $association)
                        <a href="{{ $association['url'] }}"
                           class="block p-4 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-500 dark:hover:border-indigo-400"
                           wire:navigate>
                            @if($association['thumbnail_url'])
                                <img src="{{ $association['thumbnail_url'] }}" alt="{{ $association['name'] }}" class="object-cover w-full rounded aspect-square" />
                            @endif
                            <span class="block mt-2 text-sm font-medium">{{ $association['name'] }}</span>
                            <span class="text-xs text-gray-500">{{ $association['type'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
