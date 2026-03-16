<header class="relative bg-white border-b border-gray-100 shadow-sm sticky top-0 z-[60]" x-data="{ mobileMenu: false }">
    <!-- Row 1: Main Header -->
    <div class="h-20 flex items-center px-4 mx-auto max-w-screen-2xl sm:px-6 lg:px-8">
        <!-- Logo -->
        <div class="flex-shrink-0">
            <a href="{{ url('/') }}" wire:navigate class="flex items-center group">
                <x-brand.logo class="w-auto h-8 text-primary-600 group-hover:scale-105 transition-transform" />
            </a>
        </div>

        <!-- Search Bar (Full Width) -->
        <div class="flex-1 mx-8 hidden sm:block">
            @livewire('components.global-search')
        </div>

        <!-- Action Icons -->
        <div class="flex items-center space-x-6">
            <!-- Location Picker -->
            <div class="hidden sm:block">
                @livewire('components.location-picker')
            </div>

            <a href="{{ auth()->check() ? route('wishlist.view') : route('login') }}" wire:navigate class="relative p-2 text-gray-400 hover:text-primary-600 transition-colors">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                @if($this->wishlistCount > 0)
                    <span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-4 h-4 px-1 flex items-center justify-center">{{ $this->wishlistCount }}</span>
                @endif
            </a>

            <!-- Cart -->
            <div class="relative">
                @livewire('components.cart')
            </div>
            
            <!-- Account -->
            <div class="hidden md:block">
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Signed in as</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            </div>
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700" wire:navigate>Orders</a>
                            <a href="{{ route('account.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700" wire:navigate>Settings</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-700 hover:text-primary-600 transition-colors" wire:navigate>
                        Sign In / Register
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Row 2: Secondary Navigation -->
    <div class="bg-gray-50 border-t border-gray-100 hidden lg:block">
        <div class="px-4 mx-auto max-w-screen-2xl sm:px-6 lg:px-8 flex items-center">
            <!-- All Departments Dropdown -->
            <div class="relative flex-shrink-0" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="bg-primary-600 text-white px-6 py-4 flex items-center space-x-3 font-bold hover:bg-primary-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span>All Departments</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" 
                     @click.away="open = false" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full left-0 w-64 bg-white shadow-2xl border-x border-b border-gray-100 z-50 py-2">
                    @foreach ($this->collections as $collection)
                        <a href="{{ route('collection.view', $collection->defaultUrl->slug) }}" 
                           class="flex items-center justify-between px-6 py-3 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors"
                           wire:navigate>
                            <span>{{ $collection->translateAttribute('name') }}</span>
                            @if($collection->children->count())
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="flex items-center space-x-8 ml-8">
                <a href="{{ url('/') }}" class="text-sm font-bold text-gray-700 hover:text-primary-600 uppercase tracking-wide py-4 {{ request()->is('/') ? 'text-primary-600 border-b-2 border-primary-600' : '' }}" wire:navigate>Home</a>
                <a href="{{ route('shop.view') }}" class="text-sm font-bold text-gray-700 hover:text-primary-600 uppercase tracking-wide py-4 {{ request()->routeIs('shop.view') ? 'text-primary-600 border-b-2 border-primary-600' : '' }}" wire:navigate>Shop</a>
                <a href="{{ route('search.view') }}" class="text-sm font-bold text-gray-700 hover:text-primary-600 uppercase tracking-wide py-4 {{ request()->routeIs('search.view') ? 'text-primary-600 border-b-2 border-primary-600' : '' }}" wire:navigate>Search</a>
                @if($this->brands->whereNotNull('defaultUrl')->isNotEmpty())
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="text-sm font-bold text-gray-700 hover:text-primary-600 uppercase tracking-wide py-4 flex items-center gap-2">
                            <span>Brands</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute left-0 mt-1 w-56 rounded-xl border border-gray-100 bg-white py-2 shadow-2xl">
                            @foreach($this->brands->whereNotNull('defaultUrl')->take(8) as $brand)
                                <a href="{{ route('brand.view', $brand->defaultUrl->slug) }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700">
                                    {{ $brand->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <a href="{{ route('cart.view') }}" class="text-sm font-bold text-gray-700 hover:text-primary-600 uppercase tracking-wide py-4 {{ request()->routeIs('cart.view') ? 'text-primary-600 border-b-2 border-primary-600' : '' }}" wire:navigate>Cart</a>
                @auth
                    <a href="{{ route('account.orders') }}" class="text-sm font-bold text-gray-700 hover:text-primary-600 uppercase tracking-wide py-4 {{ request()->routeIs('account.orders*') ? 'text-primary-600 border-b-2 border-primary-600' : '' }}" wire:navigate>Orders</a>
                @endauth
                @if($this->canAccessAdmin)
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="text-sm font-bold text-gray-700 hover:text-primary-600 uppercase tracking-wide py-4">Dashboard</a>
                @endif
            </nav>

            <!-- Promo/Help -->
            <div class="ml-auto flex items-center space-x-6">
                @livewire('components.currency-switcher')
                <div class="text-sm font-medium text-gray-500">
                    Need Help? <span class="text-primary-600 font-bold ml-1">+123 456 789</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation (Row 3 simplified for now) -->
    <div class="lg:hidden flex items-center justify-between px-4 py-2 bg-gray-50 border-t border-gray-100">
        <button @click="mobileMenu = !mobileMenu" class="p-2 text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <div class="flex-1 px-4">
            @livewire('components.global-search')
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenu" 
             x-cloak
             class="fixed inset-0 z-[1000]"
             @keydown.escape.window="mobileMenu = false">
            <div x-show="mobileMenu" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>
            
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-y-0 left-0 w-80 bg-white shadow-2xl z-[1001] flex flex-col">
                
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <x-brand.logo class="h-6 w-auto text-primary-600" />
                    <button @click="mobileMenu = false" class="text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto py-4">
                    <div class="px-6 mb-6">
                        @livewire('components.location-picker')
                    </div>
                    
                    <nav class="space-y-1">
                        <a href="{{ route('home') }}" wire:navigate class="block px-6 py-3 text-lg font-bold text-gray-900 border-b border-gray-50">
                            Home
                        </a>
                        <a href="{{ route('shop.view') }}" wire:navigate class="block px-6 py-3 text-lg font-bold text-gray-900 border-b border-gray-50">
                            Shop
                        </a>
                        <a href="{{ route('search.view') }}" wire:navigate class="block px-6 py-3 text-lg font-bold text-gray-900 border-b border-gray-50">
                            Search
                        </a>
                        @if(auth()->check())
                            <a href="{{ route('wishlist.view') }}" wire:navigate class="block px-6 py-3 text-lg font-bold text-gray-900 border-b border-gray-50">
                                Wishlist
                            </a>
                        @endif
                        @foreach ($this->collections as $collection)
                            <a href="{{ route('collection.view', $collection->defaultUrl->slug) }}" class="block px-6 py-3 text-lg font-bold text-gray-900 border-b border-gray-50" wire:navigate>
                                {{ $collection->translateAttribute('name') }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    @auth
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</div>
                        </div>
                        <a href="{{ route('account.orders') }}" class="block w-full text-center bg-white border border-gray-200 py-3 rounded-xl font-bold mb-2">My Orders</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-center text-red-600 font-bold py-3">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-primary-600 text-white py-4 rounded-xl font-extrabold shadow-lg shadow-primary-200 mb-4 transition-transform active:scale-95">Sign In</a>
                        <a href="{{ route('register') }}" class="block w-full text-center border-2 border-gray-200 py-3 rounded-xl font-bold">Create Account</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
