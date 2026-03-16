<section class="my-14">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center items-center">
            <div class="w-full md:w-1/2 lg:w-5/12 order-2 lg:order-1 px-4 text-center">
                <img src="https://freshcart.codescandy.com/tailwindcss/assets/images/svg-graphics/fp-g.svg" alt="Reset Password" class="w-full h-auto max-w-sm mx-auto">
            </div>
            <div class="w-full md:w-1/2 lg:w-4/12 lg:ml-auto order-1 lg:order-2 mb-8 lg:mb-0 px-4">
                <div class="mb-10">
                    <h1 class="mb-2 text-3xl font-bold">Reset Password</h1>
                    <p class="text-gray-600">Enter your new password below to reset your account access.</p>
                </div>

                <form wire:submit="resetPassword" class="space-y-4">
                    <input type="hidden" wire:model="token">

                    <div>
                        <label for="email" class="block text-sm font-semibold mb-1">Email address</label>
                        <input wire:model="email" type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-600 focus:border-green-600 outline-none" placeholder="Email address" required readonly>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold mb-1">New Password</label>
                        <input wire:model="password" type="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-600 focus:border-green-600 outline-none" placeholder="New Password" required>
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold mb-1">Confirm New Password</label>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-600 focus:border-green-600 outline-none" placeholder="Confirm New Password" required>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300 flex justify-center items-center">
                            <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                            <span wire:loading wire:target="resetPassword" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Resetting...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
