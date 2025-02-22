<div>
    <div class="py-6 flex flex-col justify-center items-center overflow-hidden" style="background: linear-gradient(to right bottom, #0f1b1e, #0d2628, #12383d, #184d50); min-height: 100vh; height: 100vh;">
        <div class="relative py-6 sm:w-3/4 lg:w-2/3 xl:w-1/2 max-w-4xl">
            <!-- Store Title Section -->
            <div class="text-center mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-teal-500 to-yellow-500">
                    Nufaisya Store
                </h1>
                <p class="text-gray-300 text-md md:text-lg italic">Your Fashion Paradise</p>
            </div>
            
            @if (session()->has('success'))
                <div class="bg-green-500 text-white p-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif
    
            <!-- Glass effect container -->
            <div class="relative px-6 py-8 bg-black bg-opacity-20 backdrop-blur-lg shadow-lg rounded-xl w-full mx-auto">
                <h2 class="text-2xl font-bold text-center mb-6 text-white">Create Account</h2>
                
                @if (session()->has('success'))
                    <div class="bg-teal-500 bg-opacity-20 text-teal-300 p-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form wire:submit.prevent="register" class="space-y-6">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Name</label>
                        <input type="text" wire:model="name" placeholder="your name" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-teal-500 focus:ring-teal-900 text-gray-100 py-2 px-3 outline-none">
                        @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                        <input type="email" wire:model="email" placeholder="email" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-teal-500 focus:ring-teal-900 text-gray-100 py-2 px-3 outline-none">
                        @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Nomer Telepon</label>
                        <input type="text" wire:model="phone_number" placeholder="No telepon" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-teal-500 focus:ring-teal-900 text-gray-100 py-2 px-3 outline-none">
                        @error('phone_number') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Password</label>
                        <input type="password" wire:model="password" placeholder="password" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-teal-500 focus:ring-teal-900 text-gray-100 py-2 px-3 outline-none">
                        @error('password') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <button type="submit" class="w-full py-3 border border-transparent rounded-lg shadow-sm text-md font-medium text-white bg-gradient-to-r from-teal-600 via-teal-500 to-yellow-500 hover:from-teal-700 hover:via-teal-600 hover:to-yellow-600 transition duration-150 ease-in-out">
                        Register
                    </button>
                </form>
                
                <p class="text-center text-gray-300 text-sm mt-4">
                    Already have an account? <a href="{{ route('login') }}" class="text-teal-400 hover:underline">Login here</a>
                </p>
            </div>
        </div>
    </div>    
</div>
