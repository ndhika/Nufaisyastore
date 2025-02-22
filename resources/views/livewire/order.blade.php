<div class="min-h-screen bg-gradient-to-br from-[#0f3d3e] via-[#1a1f3c] to-[#0a0a0a] flex items-center justify-center py-10 px-4">
    <div class="max-w-4xl w-full bg-gradient-to-br from-gray-800 via-gray-900 to-black text-white shadow-xl rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-3xl font-bold text-yellow-500">Detail Pesanan</h2>
        </div>

        <div class="p-6">
            <!-- Informasi Pesanan -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-300">Informasi Pesanan</h3>
                <div class="mt-3 space-y-2 text-gray-400">
                    <p><strong>Nomor Pesanan:</strong> {{ $orderUser->id }}</p>
                    <p><strong>ID Transaksi:</strong> {{ $orderUser->booking_trx_id }}</p>
                    <p><strong>Tanggal:</strong> {{ $orderUser->created_at->format('d M Y') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="font-bold text-yellow-500">
                            {{ 
                                [
                                    'paid' => 'Sudah Membayar',
                                    'processing' => 'Sedang Dikemas',
                                    'shipping' => 'Dikirim',
                                    'arrived' => 'Barang Sampai',
                                    'waiting_confirmation' => 'Menunggu Konfirmasi User',
                                    'done' => 'Selesai',
                                ][$orderUser->status] ?? 'Status Tidak Diketahui' 
                            }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Produk yang Dibeli -->
            <h3 class="text-xl font-semibold text-gray-300">Produk yang Dibeli</h3>
            <div class="mt-4 space-y-4">
                @foreach ($orderUser->products as $product)
                <div class="flex gap-4 bg-gradient-to-r from-gray-700 via-gray-800 to-gray-900 p-4 rounded-lg shadow-md">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" class="w-20 h-20 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-lg text-gray-200">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-400">Ukuran: {{ $product->sizes->pluck('size')->join(', ') }}</p>
                        <p class="text-lg font-semibold text-yellow-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Ringkasan Pembayaran -->
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-300">Ringkasan Pembayaran</h3>
                <div class="mt-3 space-y-2 text-gray-400">
                    <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($orderUser->sub_total_amount, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Biaya Pengiriman</span><span>Rp {{ number_format($shipping, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Pajak (12%)</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Asuransi</span><span>Rp {{ number_format($insurance, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Biaya Admin</span><span>Rp {{ number_format($adminfee, 0, ',', '.') }}</span></div>
                    @if ($discount > 0)
                    <div class="flex justify-between text-red-500"><span>Diskon</span><span>- Rp {{ number_format($discount, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="border-t my-2 border-gray-700"></div>
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-yellow-500">Rp {{ number_format($total_price, 0, ',', '.') }}</span>
                    </div>                
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6">
                @if($orderUser->status == 'waiting_confirmation')
                    <button wire:click="markAsDone" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg shadow hover:bg-green-700 transition-all">
                        Konfirmasi Selesai
                    </button>
                @else
                    <a href="{{ route('home') }}" class="w-full block text-center bg-yellow-500 text-black font-semibold py-3 rounded-lg shadow hover:bg-yellow-400 transition-all">
                        Kembali ke Beranda
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
