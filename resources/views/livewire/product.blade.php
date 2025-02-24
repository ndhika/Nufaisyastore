<div>
    @livewire('partials.navbar')
    <!--  background website -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#02505a] to-[#001834]"></div>
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative z-10 mb-6 flex justify-end mr-5 gap-2 pt-28">
        <select wire:model="category" wire:change="$refresh" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-[150px] md:w-[200px]">
            <option value="">Semua Kategori</option>
            <option value="Kacamata">Kacamata</option>
            <option value="Tas">Tas</option>
            <option value="Gamis">Gamis</option>
        </select>
        <div class="relative w-[150px] md:w-[250px]">
            <input type="text" wire:model.debounce.500ms="search" wire:keydown.enter="$refresh" placeholder="Cari produk..." class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full pr-8">
            <i class="fas fa-search text-gray-400 absolute top-1/2 transform -translate-y-1/2 right-3"></i>
        </div>
    </div>
    <!-- Tambahkan setelah div search dan sebelum grid produk -->
    @if (session()->has('message'))
    <div class="relative z-10 items-end max-w-md mt-4 transition-all duration-300 ease-in-out">
        <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-lg shadow-lg overflow-hidden">
            <div class="px-4 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-white font-medium">{{ session('message') }}</p>
                </div>
                <button wire:click="$refresh" class="text-white hover:text-gray-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif
    <h1 class=" relative z-10 text-3xl font-bold text-white mb-8 mt-12 px-6">Semua Produk</h1>
    <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6 px-6">
        @forelse ($products as $product)
            <a href="{{ route('product.detail', $product->slug) }}" wire:navigate class="block">
                <div class="bg-white rounded-lg drop-shadow-xl overflow-hidden transition-all duration-200 hover:shadow-2xl hover:scale-[1.03] active:scale-100">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-black text-lg">{{ $product->name }}</h3>
                            <p class="text-gray-700 text-sm bg-gray-100 rounded-xl px-3 py-1 ml-2">{{ $product->category }}</p>
                        </div>
                        <p class="text-gray-600 text-sm mb-2">{{ $product->description }}</p>
                        <p class="text-lg font-semibold text-blue-600 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <div class="flex flex-col h-full">
                            <button
                                @class([
                                    'w-full py-2 px-4 rounded-lg text-white text-center transition-colors items-end',
                                    'bg-blue-600 hover:bg-blue-700' => $product->stock > 0,
                                    'bg-gray-400 cursor-not-allowed' => $product->stock === 0
                                ])
                                @disabled($product->stock === 0)>
                                @if($product->stock === 0)
                                    Stok Habis
                                @else
                                    Tambah ke Keranjang
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </a>
        @empty
        <div class="col-span-full h-8 flex justify-center items-center">
            <p class="text-gray-200">Tidak ada produk yang ditemukan.</p>
        </div>     
        @endforelse
    </div>
    <!-- Pagination -->
    <div class="mt-6 px-6">
        {{ $products->links() }}
    </div>
    @livewire('partials.footer')
</div>