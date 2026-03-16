<header class="relative border-b border-gray-100">
    <div class="flex items-center justify-between h-16 px-4 mx-auto max-w-screen-2xl sm:px-6 lg:px-8">
        <div class="flex items-center">
            <a class="flex items-center flex-shrink-0"
               href="{{ url('/') }}"
               wire:navigate
            >
                <span class="sr-only">Home</span>

                <x-brand.logo class="w-auto h-6 text-green-600" />
            </a>

            <nav class="hidden lg:gap-8 lg:flex lg:ml-8">
                @foreach ($this->collections as $collection)
                    @if($collection->children->count())
                        <div x-data="{ open: false }" class="relative group">
                            <button @mouseover="open = true" @mouseleave="open = false" class="flex items-center text-sm font-medium transition hover:opacity-75">
                                <a href="{{ route('collection.view', $collection->defaultUrl->slug) }}" wire:navigate>
                                    {{ $collection->translateAttribute('name') }}
                                </a>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" @mouseover="open = true" @mouseleave="open = false" x-cloak class="absolute left-0 z-50 w-48 py-2 mt-0 bg-white border border-gray-100 shadow-xl rounded-xl">
                                @foreach($collection->children as $child)
                                    <a href="{{ route('collection.view', $child->defaultUrl->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" wire:navigate>
                                        {{ $child->translateAttribute('name') }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a class="text-sm font-medium transition hover:opacity-75"
                           href="{{ route('collection.view', $collection->defaultUrl->slug) }}"
                           wire:navigate
                        >
                            {{ $collection->translateAttribute('name') }}
                        </a>
                    @endif
                @endforeach

                @foreach ($this->brands as $brand)
                    @if($brand->defaultUrl)
                        <a class="text-sm font-medium transition hover:opacity-75"
                           href="{{ route('brand.view', $brand->defaultUrl->slug) }}"
                           wire:navigate
                        >
                            {{ $brand->translateAttribute('name') }}
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>

        <div class="flex items-center justify-between flex-1 ml-4 lg:justify-end gap-4">
            <x-header.search class="max-w-sm mr-4" />
            @livewire('components.currency-switcher')
            <div class="flex items-center -mr-4 sm:-mr-6 lg:mr-0">
                @auth
                    <div class="hidden lg:flex lg:items-center lg:ml-6 lg:gap-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="text-sm font-medium text-gray-700 hover:text-green-600 transition">
                                My Account ({{ auth()->user()->name }})
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white p-1 shadow-lg ring-1 ring-black ring-opacity-5">
                                <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" wire:navigate>Orders</a>
                                <a href="{{ route('account.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" wire:navigate>Settings</a>
                                <a href="{{ route('account.addresses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" wire:navigate>Addresses</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded-md">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden lg:flex lg:items-center lg:ml-6 gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition" wire:navigate>
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition" wire:navigate>
                            Register
                        </a>
                    </div>
                @endauth

                @livewire('components.cart')

                <div x-data="{ mobileMenu: false }">
                    <button x-on:click="mobileMenu = !mobileMenu"
                            class="grid flex-shrink-0 w-16 h-16 border-l border-gray-100 lg:hidden">
                        <span class="sr-only">Toggle Menu</span>

                        <span class="place-self-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </span>
                    </button>

                    <div x-cloak
                         x-transition
                         x-show="mobileMenu"
                         class="absolute right-0 top-auto z-50 w-screen p-4 sm:max-w-xs">
                        <ul x-on:click.away="mobileMenu = false"
                            class="p-6 space-y-4 bg-white border border-gray-100 shadow-xl rounded-xl">
                            @auth
                                <li>
                                    <span class="text-sm font-medium text-gray-700 block mb-2">Hi, {{ auth()->user()->name }}</span>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-red-600">Logout</button>
                                    </form>
                                </li>
                                <hr class="border-gray-100">
                            @else
                                <li>
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 block" wire:navigate>
                                        Sign In
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 block" wire:navigate>
                                        Create Account
                                    </a>
                                </li>
                                <hr class="border-gray-100">
                            @endauth

                            @foreach ($this->collections as $collection)
                                <li x-data="{ open: false }">
                                    <div class="flex items-center justify-between">
                                        <a class="text-sm font-medium"
                                           href="{{ route('collection.view', $collection->defaultUrl->slug) }}"
                                           wire:navigate
                                        >
                                            {{ $collection->translateAttribute('name') }}
                                        </a>
                                        @if($collection->children->count())
                                            <button @click="open = !open" class="p-1">
                                                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if($collection->children->count())
                                        <ul x-show="open" x-cloak class="mt-2 ml-4 space-y-2 border-l border-gray-100 pl-4">
                                            @foreach($collection->children as $child)
                                                <li>
                                                    <a href="{{ route('collection.view', $child->defaultUrl->slug) }}" class="text-sm text-gray-600 hover:text-green-600" wire:navigate>
                                                        {{ $child->translateAttribute('name') }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
