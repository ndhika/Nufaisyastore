<div>
    <div class="py-6 flex flex-col justify-center items-center" style="background: linear-gradient(to right bottom, #1a1a2e, #16213e, #1b2a4a, #233876); min-height: 100vh;">
        <div class="relative py-6 sm:w-3/4 lg:w-2/3 xl:w-1/2 max-w-4xl">
            <!-- Store Title Section -->
            <div class="text-center mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500">
                    Nufaisya Store
                </h1>
                <p class="text-gray-300 text-md md:text-lg italic">Your Fashion Paradise</p>
            </div>
    
            <!-- Glass effect container -->
            <div class="relative px-6 py-8 bg-black bg-opacity-20 backdrop-blur-lg shadow-lg rounded-xl w-full mx-auto">
                <h2 class="text-2xl font-bold text-center mb-6 text-white">Create Account</h2>
                
                @if (session()->has('success'))
                    <div class="bg-green-500 bg-opacity-20 text-green-300 p-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form wire:submit.prevent="register" class="space-y-6">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Name</label>
                        <input type="text" wire:model="name" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-indigo-500 focus:ring-indigo-900 text-gray-100 py-2 px-3 outline-none">
                        @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                        <input type="email" wire:model="email" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-indigo-500 focus:ring-indigo-900 text-gray-100 py-2 px-3 outline-none">
                        @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Phone Number</label>
                        <input type="text" wire:model="phone_number" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-indigo-500 focus:ring-indigo-900 text-gray-100 py-2 px-3 outline-none">
                        @error('phone_number') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Password</label>
                        <input type="password" wire:model="password" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-indigo-500 focus:ring-indigo-900 text-gray-100 py-2 px-3 outline-none">
                        @error('password') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <button type="submit" class="w-full py-3 border border-transparent rounded-lg shadow-sm text-md font-medium text-white bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 hover:from-pink-600 hover:via-purple-600 hover:to-indigo-600 transition duration-150 ease-in-out">
                        Join Nufaisya Store
                    </button>
                </form>
            </div>
        </div>
    </div>    
</div>
