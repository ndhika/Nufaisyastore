<div>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold text-center mb-4">Register</h2>
    
        @if (session()->has('success'))
            <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
    
        <form wire:submit.prevent="register">
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input type="text" wire:model="name" class="w-full p-2 border rounded">
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
    
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" wire:model="email" class="w-full p-2 border rounded">
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
    
            <div class="mb-4">
                <label class="block text-gray-700">Phone Number</label>
                <input type="text" wire:model="phone_number" class="w-full p-2 border rounded">
                @error('phone_number') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
    
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" wire:model="password" class="w-full p-2 border rounded">
                @error('password') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
    
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                Register
            </button>
        </form>
    </div>
</div>
