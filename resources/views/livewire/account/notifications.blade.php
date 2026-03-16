<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
            @include('livewire.account.sidebar')
        </aside>

        <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form wire:submit="updatePreferences">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Notifications</h3>
                    </div>

                    @if (session('status'))
                        <div class="mx-4 mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                            <p class="text-sm text-green-700">{{ session('status') }}</p>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 px-4 py-6 space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" wire:model="preferences.order_updates" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label class="font-medium text-gray-700">Order Updates</label>
                                <p class="text-gray-500">Receive notifications about your order status changes.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" wire:model="preferences.promotions" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label class="font-medium text-gray-700">Promotions</label>
                                <p class="text-gray-500">Receive emails about new products, sales, and special offers.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" wire:model="preferences.stock_alerts" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label class="font-medium text-gray-700">Stock Alerts</label>
                                <p class="text-gray-500">Get notified when products on your wishlist are back in stock.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" wire:model="preferences.newsletter" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label class="font-medium text-gray-700">Newsletter</label>
                                <p class="text-gray-500">Subscribe to our monthly newsletter for latest news.</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                            Update Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
