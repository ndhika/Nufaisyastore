<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Detail Pesanan</h2>

        <div class="card bg-base-100 shadow-xl p-6">
            <h3 class="text-xl font-semibold mb-4">Informasi Pesanan</h3>

            <div class="mb-4">
                <p><strong>Nomor Pesanan:</strong> {{ $orderUser->id }}</p>
                <p><strong>ID Transaksi:</strong> {{ $orderUser->booking_trx_id }}</p>
                <p><strong>Tanggal:</strong> {{ $orderUser->created_at->format('d M Y') }}</p>
                <p><strong>Status:</strong> 
                    @php
                        $statusText = [
                            'paid' => 'Sudah Membayar',
                            'processing' => 'Sedang Dikemas',
                            'shipping' => 'Dikirim',
                            'arrived' => 'Barang Sampai',
                            'waiting_confirmation' => 'Menunggu Konfirmasi User',
                            'done' => 'Selesai',
                        ][$orderUser->status] ?? 'Status Tidak Diketahui';
                    @endphp
                    <span class="text-primary font-bold">{{ $statusText }}</span>
                </p>
            </div>

            <h3 class="text-xl font-semibold mt-6">Produk yang Dibeli</h3>

            @foreach ($orderUser->products as $product)
            <div class="mt-6 bg-white shadow-md rounded-lg p-4 flex gap-4 items-center">
                <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                    class="w-24 h-24 object-cover rounded-lg">
                <div class="flex-1">
                    <h4 class="font-medium text-lg">{{ $product->name }}</h4>
                    <p class="text-sm text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Ukuran: {{ $product->sizes->pluck('size')->join(', ') }}</p>
                    <p class="font-semibold text-xl text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach

            <div class="mt-6">
                <h3 class="text-xl font-semibold">Ringkasan Pembayaran</h3>
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($orderUser->sub_total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Biaya Pengiriman</span>
                    <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pajak (12%)</span>
                    <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Asuransi</span>
                    <span>Rp {{ number_format($insurance, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Biaya Admin</span>
                    <span>Rp {{ number_format($adminfee, 0, ',', '.') }}</span>
                </div>
                @if ($discount > 0)
                <div class="flex justify-between text-red-500">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="border-t my-2"></div>
                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span class="text-primary">
                        Rp {{ number_format($total_price, 0, ',', '.') }}
                    </span>
                </div>                
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6">
                @if($orderUser->status == 'waiting_confirmation')
                    <button wire:click="markAsDone" class="btn btn-success w-full">
                        Konfirmasi Selesai
                    </button>
                @else
                    <button class="btn btn-secondary w-full" onclick="location.href='/'">
                        Kembali ke Beranda
                    </button>
                @endif
            </div>
        </div>
    </div>    
</div>
