<div class="bg-white p-6 shadow-sm rounded-lg">
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-900">Dashboard</h3>
        <nav class="space-y-1">
            <a href="{{ route('account.orders') }}" @class([
                'flex items-center px-3 py-2 text-sm font-medium rounded-md',
                'bg-blue-50 text-blue-700' => request()->routeIs('account.orders'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('account.orders'),
            ])>
                <x-lucide-shopping-bag class="mr-3 h-5 w-5" />
                Your Orders
            </a>
            <a href="{{ route('account.settings') }}" @class([
                'flex items-center px-3 py-2 text-sm font-medium rounded-md',
                'bg-blue-50 text-blue-700' => request()->routeIs('account.settings'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('account.settings'),
            ])>
                <x-lucide-settings class="mr-3 h-5 w-5" />
                Settings
            </a>
            <a href="{{ route('account.addresses') }}" @class([
                'flex items-center px-3 py-2 text-sm font-medium rounded-md',
                'bg-blue-50 text-blue-700' => request()->routeIs('account.addresses'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('account.addresses'),
            ])>
                <x-lucide-map-pin class="mr-3 h-5 w-5" />
                Address
            </a>
            <a href="{{ route('account.payment-methods') }}" @class([
                'flex items-center px-3 py-2 text-sm font-medium rounded-md',
                'bg-blue-50 text-blue-700' => request()->routeIs('account.payment-methods'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('account.payment-methods'),
            ])>
                <x-lucide-credit-card class="mr-3 h-5 w-5" />
                Payment Method
            </a>
            <a href="{{ route('account.notifications') }}" @class([
                'flex items-center px-3 py-2 text-sm font-medium rounded-md',
                'bg-blue-50 text-blue-700' => request()->routeIs('account.notifications'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('account.notifications'),
            ])>
                <x-lucide-bell class="mr-3 h-5 w-5" />
                Notification
            </a>
        </nav>
    </div>
</div>
