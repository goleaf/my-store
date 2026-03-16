<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
            @include('livewire.account.sidebar')
        </aside>

        <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Order #{{ $order->reference }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Placed on {{ $order->placed_at?->format('F d, Y') ?? $order->created_at->format('F d, Y') }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        {{ $order->status_label }}
                    </span>
                </div>

                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Shipping Address</h4>
                            @if($order->shippingAddress)
                                <address class="not-italic text-sm text-gray-600">
                                    {{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}<br>
                                    {{ $order->shippingAddress->line_one }}<br>
                                    @if($order->shippingAddress->line_two) {{ $order->shippingAddress->line_two }}<br> @endif
                                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postcode }}<br>
                                    {{ $order->shippingAddress->country->name }}
                                </address>
                            @else
                                <p class="text-sm text-gray-500">No shipping address provided.</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Billing Address</h4>
                            @if($order->billingAddress)
                                <address class="not-italic text-sm text-gray-600">
                                    {{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}<br>
                                    {{ $order->billingAddress->line_one }}<br>
                                    @if($order->billingAddress->line_two) {{ $order->billingAddress->line_two }}<br> @endif
                                    {{ $order->billingAddress->city }}, {{ $order->billingAddress->postcode }}<br>
                                    {{ $order->billingAddress->country->name }}
                                </address>
                            @else
                                <p class="text-sm text-gray-500">No billing address provided.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 overflow-hidden border border-gray-100 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($order->lines as $line)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ $line->purchasable?->getThumbnail()?->getUrl('small') }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $line->description }}</div>
                                                    <div class="text-xs text-gray-500">{{ $line->identifier }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $line->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                            {{ $line->unit_price->formatted() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            {{ $line->sub_total->formatted() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Subtotal</td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">{{ $order->sub_total->formatted() }}</td>
                                </tr>
                                @if($order->discount_total->value > 0)
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-green-600">Discount</td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-green-600">-{{ $order->discount_total->formatted() }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Tax</td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">{{ $order->tax_total->formatted() }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900">Total</td>
                                    <td class="px-6 py-3 text-right text-sm font-bold text-indigo-600 text-lg">{{ $order->total->formatted() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex justify-start">
                <a href="{{ route('account.orders') }}" class="text-indigo-600 hover:text-indigo-900 font-medium" wire:navigate>&larr; Back to orders</a>
            </div>
        </div>
    </div>
</div>
