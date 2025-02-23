<div class="min-h-screen flex justify-center items-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-700 p-6">
    <div class="w-full max-w-5xl bg-gray-800 p-8 rounded-2xl shadow-xl text-white relative">
        
        {{-- Tombol Kembali --}}
        <a href="{{ route('home') }}" 
            class="absolute top-6 left-6 px-4 py-2 text-sm font-semibold text-white bg-gray-700 hover:bg-gray-600 rounded-lg shadow flex items-center space-x-2">
            🔙 <span>Kembali</span>
        </a>

        <h2 class="text-3xl font-bold mb-6 text-center">📜 History Pemesanan</h2>

        <div class="overflow-x-auto rounded-lg">
            <table class="w-full bg-gray-900 shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="p-4 text-left">#</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-left">Total</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $index => $order)
                        @php
                            $statusText = [
                                'paid' => 'Sudah Membayar',
                                'processing' => 'Sedang Dikemas',
                                'shipping' => 'Dikirim',
                                'arrived' => 'Barang Sampai',
                                'waiting_confirmation' => 'Menunggu Konfirmasi',
                                'done' => 'Selesai'
                            ];

                            $statusColor = [
                                'paid' => 'bg-blue-500',
                                'processing' => 'bg-yellow-500',
                                'shipping' => 'bg-purple-500',
                                'arrived' => 'bg-indigo-500',
                                'waiting_confirmation' => 'bg-orange-500',
                                'done' => 'bg-green-500'
                            ];
                        @endphp

                        <tr class="border-b border-gray-700 hover:bg-gray-800 transition duration-300">
                            <td class="p-4 font-medium">{{ $index + 1 }}</td>

                            {{-- Gambar Produk --}}
                            <td class="p-4 flex items-center space-x-3">
                                @foreach ($order->products as $product)
                                    <div class="flex flex-col items-start">
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                            alt="{{ $product->name }}" 
                                            class="w-16 h-16 object-cover rounded-lg shadow-md">
                                        <span class="text-xs text-gray-300 mt-1">{{ $product->name }}</span>
                                    </div>
                                @endforeach
                            </td>

                            <td class="p-4">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="p-4 font-semibold text-green-400">Rp {{ number_format($order->grand_total_amount, 0, ',', '.') }}</td>

                            {{-- Status --}}
                            <td class="p-4">
                                <span class="px-4 py-1 text-sm font-semibold text-white rounded-full 
                                    {{ $statusColor[$order->status] ?? 'bg-gray-600' }}">
                                    {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </td>

                            {{-- Tombol Konfirmasi --}}
                            <td class="p-4">
                                @if ($order->status == 'waiting_confirmation')
                                    <button wire:click="confirmOrder({{ $order->id }})" 
                                            class="px-4 py-1 text-sm font-semibold text-white bg-green-500 hover:bg-green-600 rounded-lg shadow">
                                        ✅ Konfirmasi
                                    </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-400">
                                Belum ada riwayat pemesanan. 🛒
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
