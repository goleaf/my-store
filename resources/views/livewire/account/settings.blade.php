<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
            @include('livewire.account.sidebar')
        </aside>

        <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
            <section aria-labelledby="profile-settings-heading">
                <form wire:submit="updateProfile">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                            <div>
                                <h3 id="profile-settings-heading" class="text-lg leading-6 font-medium text-gray-900">Account Setting</h3>
                            </div>

                            @if (session('status'))
                                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <x-lucide-check-circle class="h-5 w-5 text-green-400" />
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-green-700">
                                                {{ session('status') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" wire:model="name" id="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                    <input type="email" wire:model="email" id="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" wire:model="phone" id="phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Profile
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            <section aria-labelledby="password-settings-heading">
                <form wire:submit="updatePassword">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                            <div>
                                <h3 id="password-settings-heading" class="text-lg leading-6 font-medium text-gray-900">Password Setting</h3>
                            </div>

                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                    <input type="password" wire:model="current_password" id="current_password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" wire:model="new_password" id="new_password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" wire:model="new_password_confirmation" id="new_password_confirmation" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Password
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
