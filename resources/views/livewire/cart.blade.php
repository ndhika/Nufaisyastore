<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-lg shadow">
                <!-- Cart Header -->
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Keranjang Belanja</h2>
                    <div class="form-control">
                        <label class="cursor-pointer label">
                            <span class="label-text text-sm text-gray-800">Pilih Semua</span>
                            <input type="checkbox" wire:click="toggleSelectAll" class="ml-5 checkbox checkbox-accent">
                        </label>
                    </div>
                </div>
                
                @if($cartItems->isEmpty())
                    <div class="p-6 text-center">
                        <p class="text-gray-500">Keranjang belanja Anda kosong</p>
                        <a href="{{ route('product') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-700">
                            Mulai Belanja
                        </a>
                    </div>
                @else
                    <!-- Cart Items List -->
                    <div class="divide-y">
                        @foreach($cartItems as $item)
                            <div class="p-6 flex space-x-6">
                                <div class="form-control">
                                    <input type="checkbox" wire:model="selectedItems" value="{{ $item->product_id }}" class="checkbox checkbox-accent" />
                                </div>
                                <!-- Product Image -->
                                <div class="flex-shrink-0 w-24 h-24">
                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg" />
                                </div>
                                <!-- Product Details -->
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-800">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Ukuran: {{ is_array($selectedItemsData[$item->product_id] ?? null) ? ($selectedItemsData[$item->product_id]['size'] ?? 'Tidak ada ukuran') : ($selectedItemsData[$item->product_id]->size ?? 'Tidak ada ukuran') }}
                                            </p>                                           
                                        </div>
                                        <!-- Remove Item Button -->
                                        <button wire:click="removeItem({{ $item->product_id }})" class="text-gray-400 hover:text-red-500">
                                            <i class="fa-regular fa-trash-can text-red-600 text-xl cursor-pointer border border-gray-600 px-2 rounded-lg"></i>
                                        </button>                                        
                                    </div>
                                    <!-- Quantity and Price Section -->
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <!-- Quantity Input -->
                                            <input type="number" wire:model="quantities.{{ $item->product_id }}" wire:change="updateQuantity({{ $item->product_id }}, $event.target.value)" min="1" max="{{ $item->product->stock }}" class="w-16 p-2 text-white border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200">
                                            <!-- Stock Info -->
                                            <span class="text-sm text-gray-500">Stok: {{ $item->product->stock }}</span>
                                        </div>
                                        <!-- Price Information -->
                                        <div class="text-right">
                                            <p class="text-lg font-medium text-gray-800">
                                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }} / item
                                            </p>
                                        </div>
                                    </div>                                  
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <!-- Order Summary Section -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-lg shadow sticky top-4">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Ringkasan Belanja</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Subtotal -->
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Harga ({{ $cartItems->count() }} Produk)</span>
                        <span class="font-medium text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <!-- Total -->
                    <div class="flex justify-between text-lg font-medium border-t pt-4">
                        <span class="text-gray-800">Total Tagihan</span>
                        <span class="text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <!-- Checkout Button -->
                    <button 
                        wire:click="checkout"
                        class="w-full py-3 px-4 rounded-lg text-white text-center transition-colors {{ $cartItems->count() > 0 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed' }}"
                        @disabled($cartItems->count() === 0)
                    >
                        Checkout ({{ $cartItems->count() }})
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
