<div class="bg-gradient-to-b from-gray-500 to-gray-900">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-4">
            <a href="{{ route('product') }}" class="flex items-center text-white hover:text-yellow-500 transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Produk
            </a>
        </div>
        @if (session()->has('success'))
            <div class="mb-4 p-4 text-green-800 bg-green-200 border border-green-400 rounded-lg">
            {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 p-4 text-red-800 bg-red-200 border border-red-400 rounded-lg">
            {{ session('error') }}
            </div>
        @endif
        @if (session()->has('info'))
            <div class="mb-4 p-4 text-blue-800 bg-blue-200 border border-blue-400 rounded-lg">
                {{ session('info') }}
            </div>
        @endif
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Section - Images -->
            <div class="w-full lg:w-2/5">
                <div class="sticky top-4">
                    <!-- Gambar Utama -->
                    <div class="rounded-lg overflow-hidden mb-4">
                        <img src="{{ asset('storage/' . $selectedImage) }}" 
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover cursor-pointer"
                            wire:click="changeImage('{{ $product->thumbnail }}')">
                    </div>

                    @if($product->photos->count() > 0)
                        <!-- Thumbnail Gambar -->
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->photos as $photo)
                                <button wire:click="changeImage('{{ $photo->photo }}')" 
                                    wire:key="photo-{{ $photo->id }}"
                                    class="aspect-square rounded-md overflow-hidden border-2 {{ $selectedImage === $photo->photo ? 'border-blue-500' : 'border-slate-200' }}">
                                    <img src="{{ asset('storage/' . $photo->photo) }}" 
                                        alt="Thumbnail {{ $loop->iteration }}"
                                        class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Section - Product Info -->
            <div class="w-full lg:w-3/5">
                <div class="bg-white rounded-lg p-6">
                    <!-- Header -->
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">
                            {{ $product->name }}
                        </h1>
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-gray-800">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        @if($product->is_popular)
                            <span class="inline-block mt-2 px-2 py-1 bg-red-100 text-red-600 text-sm rounded">
                                Produk Populer
                            </span>
                        @endif
                    </div>

                    <!-- Variant Selection -->
                    @if($product->sizes->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Pilih Ukuran</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->sizes as $size)
                                    <button wire:click="selectSize({{ $size->id }})" 
                                        class="px-4 py-2 rounded-lg border text-sm font-medium {{ $selectedSize === $size->id ? 'border-blue-500 bg-blue-100 text-blue-600' : 'border-gray-400 text-gray-700 hover:border-gray-600' }}">
                                        {{ $size->size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Jumlah</h3>
                        <div class="flex items-center gap-4">
                            <button wire:click="decrementQuantity"
                                class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-500 text-gray-500 hover:border-gray-800 hover:text-gray-700 transition"
                                {{ $quantity <= 1 ? 'disabled' : '' }}>
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            
                            <span class="w-12 text-center font-medium text-gray-900">{{ $quantity }}</span>

                            <button wire:click="incrementQuantity"
                                class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-500 text-gray-500 hover:border-gray-800 hover:text-gray-700 transition"
                                {{ $quantity >= $product->stock ? 'disabled' : '' }}>
                                <i class="fa-solid fa-plus"></i>
                            </button>

                            <span class="text-sm text-gray-500">
                                Stok: {{ $product->stock }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex mb-8">
                        <button wire:click="addToCart({{ $product->id }}, '{{ $selectedSize }}', {{ $quantity }})" 
                            class="flex-1 px-6 py-3 rounded-lg flex items-center justify-center gap-2 transition-colors
                            {{ $product->stock > 0 ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-400 text-gray-700 cursor-not-allowed' }}"
                            {{ $product->stock == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart w-5 h-5"></i>
                            {{ $product->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                    </div>

                    <!-- Product Details -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Detail Produk</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500 mb-2">Kategori</p>
                                <p class="font-medium">{{ $product->category }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-gray-500 mb-2">Deskripsi</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-line">
                                {{ $product->about }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>


