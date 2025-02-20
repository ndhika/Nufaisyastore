<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md space-y-8">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl font-bold mb-4 justify-center">Login</h2>
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-200 text-red-800 p-2 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="login">
                    <!-- Email Input -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Email</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="email"
                            class="input input-bordered @error('email') input-error @enderror" 
                            required 
                            autofocus
                        />
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                
                    <!-- Password Input dengan Toggle -->
                    <div class="form-control mt-4" x-data="{ show: false }">
                        <label class="label">
                            <span class="label-text">Password</span>
                        </label>
                        <div class="relative">
                            <input 
                                :type="show ? 'text' : 'password'" 
                                wire:model="password"
                                class="input input-bordered w-full @error('password') input-error @enderror" 
                                required 
                            />
                            <button type="button" @click="show = !show" class="absolute right-3 top-3 text-gray-500">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                
                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan Alpine.js untuk toggle -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Tambahkan Font Awesome jika belum -->
<script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
