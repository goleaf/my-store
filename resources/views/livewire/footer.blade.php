<footer class="bg-gray-50 border-t border-gray-100 mt-12">
    <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div>
                <x-brand.logo class="w-auto h-8 text-indigo-600" />
                <p class="max-w-xs mt-4 text-gray-700">
                    This is an example of a classic e-commerce store built with Laravel.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-8 lg:col-span-2 sm:grid-cols-3">
                <div>
                    <p class="font-medium text-gray-900">Collections</p>
                    <nav class="flex flex-col mt-4 space-y-2 text-sm text-gray-500">
                        @foreach($this->collections as $collection)
                            <a class="hover:opacity-75" href="{{ route('collection.view', $collection->defaultUrl->slug) }}" wire:navigate>
                                {{ $collection->translateAttribute('name') }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div>
                    <p class="font-medium text-gray-900">Brands</p>
                    <nav class="flex flex-col mt-4 space-y-2 text-sm text-gray-500">
                        @foreach($this->brands as $brand)
                            @if($brand->defaultUrl)
                                <a class="hover:opacity-75" href="{{ route('brand.view', $brand->defaultUrl->slug) }}" wire:navigate>
                                    {{ $brand->translateAttribute('name') }}
                                </a>
                            @endif
                        @endforeach
                    </nav>
                </div>

                <div>
                    <p class="font-medium text-gray-900">Account</p>
                    <nav class="flex flex-col mt-4 space-y-2 text-sm text-gray-500">
                        @auth
                            <a class="hover:opacity-75" href="{{ route('account.orders') }}" wire:navigate>Orders</a>
                            <a class="hover:opacity-75" href="{{ route('account.settings') }}" wire:navigate>Settings</a>
                        @else
                            <a class="hover:opacity-75" href="{{ route('login') }}" wire:navigate>Sign In</a>
                            <a class="hover:opacity-75" href="{{ route('register') }}" wire:navigate>Register</a>
                        @endauth
                    </nav>
                </div>
            </div>
        </div>

        <p class="pt-8 mt-12 text-sm text-gray-500 border-t border-gray-100">
            &copy; {{ now()->year }} Company Name. All rights reserved.
        </p>
    </div>
</footer>
