<div class="min-h-screen flex items-center justify-center p-4 relative" style="background: linear-gradient(to right bottom, #0f1b1e, #0d2628, #12383d, #184d50);">
    <div class="relative w-full max-w-md space-y-8">
        <div class="bg-black bg-opacity-20 backdrop-blur-lg shadow-lg rounded-xl overflow-hidden p-6">
            <h2 class="text-3xl font-bold text-center text-white mb-6">Welcome Back!</h2>
            
            @if (session('status'))
                <div class="bg-teal-500 bg-opacity-20 text-teal-300 p-3 rounded-lg mb-4">
                    {{ session('status') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-500 bg-opacity-20 text-red-300 p-3 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="login" class="space-y-6">
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                    <input type="email" wire:model="email" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-teal-500 focus:ring-teal-900 text-gray-100 py-2 px-3 outline-none">
                    @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            
                <div x-data="{ show: false }">
                    <label class="block text-gray-300 text-sm font-medium mb-2">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" wire:model="password" class="w-full bg-gray-800 bg-opacity-50 rounded-lg border border-gray-600 focus:border-teal-500 focus:ring-teal-900 text-gray-100 py-2 px-3 outline-none">
                        <button type="button" @click="show = !show" class="absolute right-3 top-2 text-gray-400 hover:text-teal-400 transition">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    @error('password') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            
                <button type="submit" class="w-full py-3 border border-transparent rounded-lg shadow-sm text-md font-medium text-white bg-gradient-to-r from-teal-600 via-teal-500 to-yellow-500 hover:from-teal-700 hover:via-teal-600 hover:to-yellow-600 transition duration-150 ease-in-out">
                    Sign in
                </button>
            </form>
            
            <p class="text-center text-gray-300 text-sm mt-4">
                Don't have an account? <a href="{{ route('register') }}" class="text-teal-400 hover:underline">Sign up here</a>
            </p>
        </div>
    </div>
</div>

<!-- Tambahkan Alpine.js untuk toggle -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Tambahkan Font Awesome jika belum -->
<script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
