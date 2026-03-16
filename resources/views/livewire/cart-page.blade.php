<section>
    <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">Your Cart</h1>

        <div class="mt-8">
            @if ($this->cart && count($lines))
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <div class="flow-root">
                            <ul class="-my-6 divide-y divide-gray-200">
                                @foreach ($lines as $index => $line)
                                    <li class="flex py-6" wire:key="line_{{ $line['id'] }}">
                                        @if($line['thumbnail'])
                                            <div class="flex-shrink-0 w-24 h-24 overflow-hidden border border-gray-200 rounded-md">
                                                <img src="{{ $line['thumbnail'] }}" alt="{{ $line['description'] }}" class="object-cover object-center w-full h-full">
                                            </div>
                                        @endif

                                        <div class="flex flex-col flex-1 ml-4">
                                            <div>
                                                <div class="flex justify-between text-base font-medium text-gray-900">
                                                    <h3>{{ $line['description'] }}</h3>
                                                    <p class="ml-4">{{ $line['sub_total'] }}</p>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500">{{ $line['identifier'] }} / {{ $line['options'] }}</p>
                                            </div>
                                            <div class="flex items-center justify-between flex-1 text-sm">
                                                <div class="flex items-center">
                                                    <label for="quantity-{{ $index }}" class="mr-2 text-gray-500">Qty</label>
                                                    <input id="quantity-{{ $index }}"
                                                           class="w-16 p-2 text-sm border border-gray-200 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                           type="number"
                                                           wire:model.live="lines.{{ $index }}.quantity" />
                                                </div>

                                                <div class="flex">
                                                    <button type="button"
                                                            wire:click="removeLine('{{ $line['id'] }}')"
                                                            class="font-medium text-indigo-600 hover:text-indigo-500">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-8">
                            <button type="button"
                                    wire:click="updateLines"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Cart
                            </button>
                        </div>

                        <div class="mt-12">
                            <h3 class="text-lg font-medium text-gray-900">Promotions</h3>
                            <div class="mt-4 flex max-w-sm">
                                <div class="relative flex-grow">
                                    <input type="text"
                                           wire:model.defer="couponCode"
                                           placeholder="Coupon code"
                                           class="w-full p-3 pr-20 text-sm border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                                        @if($this->cart->coupon_code)
                                            <button type="button"
                                                    wire:click="removeCoupon"
                                                    class="px-4 py-2 text-xs font-bold text-red-600 hover:text-red-700">
                                                Remove
                                            </button>
                                        @else
                                            <button type="button"
                                                    wire:click="applyCoupon"
                                                    class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                                Apply
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if (session()->has('coupon_status'))
                                <p class="mt-2 text-sm text-green-600 font-medium">
                                    {{ session('coupon_status') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="p-6 bg-gray-50 rounded-lg shadow-sm sticky top-6">
                            <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
                            <div class="mt-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-600">Subtotal</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $this->cart->subTotal->formatted() }}</p>
                                </div>

                                @if ($this->cart->discountTotal->value > 0)
                                    <div class="flex items-center justify-between text-green-600">
                                        <p class="text-sm">Discount</p>
                                        <p class="text-sm font-medium">-{{ $this->cart->discountTotal->formatted() }}</p>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-600">Tax</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $this->cart->taxTotal->formatted() }}</p>
                                </div>

                                <div class="pt-4 border-t border-gray-200 flex items-center justify-between">
                                    <p class="text-base font-medium text-gray-900">Order total</p>
                                    <p class="text-base font-medium text-gray-900">{{ $this->cart->total->formatted() }}</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('checkout.view') }}"
                                   wire:navigate
                                   class="flex items-center justify-center w-full px-6 py-3 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                                    Checkout
                                </a>
                            </div>

                            <div class="mt-6 text-sm text-center">
                                <p>
                                    or
                                    <a href="{{ url('/') }}" wire:navigate class="font-medium text-indigo-600 hover:text-indigo-500">
                                        Continue Shopping<span aria-hidden="true"> &rarr;</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h2 class="mt-2 text-lg font-medium text-gray-900">Your cart is empty</h2>
                    <p class="mt-1 text-sm text-gray-500">Start adding some products to your cart!</p>
                    <div class="mt-6">
                        <a href="{{ url('/') }}"
                           wire:navigate
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            Go Shopping
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
