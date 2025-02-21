<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Section -->
            <div class="w-full lg:w-2/3">
                <!-- Shipping Address Card -->
                <div class="card bg-base-100 shadow-xl mb-6">
                    <div class="card-body">
                        <h2 class="card-title text-xl mb-4 text-white">Alamat Pengiriman</h2>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-success/20 flex items-center justify-center">
                                <span class="text-success text-lg">📍</span>
                            </div>
                            <div>
                                <h3 class="text-white font-medium">{{ auth()->user()->name }}</h3>
                                <p class="text-white text-base-content/70 text-sm mt-1">
                                    Alamat: {{ session('temp_address', '-') }}<br>
                                    Kota: {{ session('temp_city', '-') }}<br>
                                    No. Telepon: {{ session('temp_phone_number', auth()->user()->phone_number ?? '-') }}
                                </p>
                                <button class="btn btn-outline btn-sm mt-3 text-white" wire:click="openModal">
                                    Ganti Alamat
                                </button>
                            </div>                            
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="card bg-base-100 shadow-xl" wire:init="loadCart">
                    <div class="card-body">
                        <h2 class="card-title text-xl mb-4 text-white">Produk</h2>

                        {{-- Tambahkan indikator loading --}}
                        <div wire:loading class="text-center">
                            <p class="text-white">Loading produk...</p>
                        </div>

                        {{-- Tampilan produk (hilang saat loading) --}}
                        <div wire:loading.remove>
                            @foreach($cartItems as $item)
                            @if($item && $item->product)
                            <div class="flex gap-4 py-4 border-b" wire:key="cart-item-{{ $item->id }}">
                                <div class="w-20 h-20">
                                    <img 
                                        src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                        alt="{{ $item->product->name }}"
                                        class="w-full h-full object-cover rounded-lg"
                                    >
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-white">{{ $item->product->name }}</h3>
                                    <p class="text-base-content/70 mt-1 text-white">
                                        {{ $item->quantity ?? '0' }} x Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }}
                                    </p>
                                    <p class="text-base-content/70 mt-1 text-white">
                                        Ukuran: {{ $item->size->size ?? 'Default' }} 
                                    </p>
                                </div>
                            </div>
                            @endif
                            @endforeach  
                        </div>
                    </div>
                </div>               
            </div>

            <!-- Right Section -->
            <div class="w-full lg:w-1/3">
                <div class="card bg-base-100 shadow-xl sticky top-4">
                    <div class="card-body">
                        <h2 class="card-title text-xl mb-4 text-white">Ringkasan Pembayaran</h2>
                        <!-- Promo Code Input -->
                        <div class="flex gap-2 mb-4">
                            <input 
                                type="text" 
                                wire:model.lazy="promoCode"
                                wire:keyup.debounce.500ms="checkPromoCode"
                                placeholder="Masukkan kode promo" 
                                class="input input-bordered w-full text-white"
                            >
                            <button 
                                wire:click="applyPromo"
                                class="btn btn-primary text-white"
                            >
                                Pakai
                            </button>
                        </div>
                        <!-- Feedback Message -->
                        @if($promoCodeMessage)
                            <div class="text-sm mt-2">
                                @if($promoCodeStatus)
                                    <span class="text-success">✅ {{ $promoCodeMessage }}</span>
                                @else
                                    <span class="text-error">❌ {{ $promoCodeMessage }}</span>
                                @endif
                            </div>
                        @endif
                        <!-- Price Details -->
                        <div class="space-y-3">
                            <div class="flex justify-between text-base-content/70 text-white">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-base-content/70 text-white">
                                <span>Biaya Pengiriman</span>
                                <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-base-content/70 text-white">
                                <span>Asuransi</span>
                                <span>Rp {{ number_format($insurance, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-base-content/70 text-white">
                                <span>Biaya Admin</span>
                                <span>Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-base-content/70 text-white">
                                <span>Pajak (12%)</span>
                                <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            @if($discount > 0)
                            <div class="flex justify-between text-success">
                                <span>Diskon</span>
                                <span>-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="divider my-2"></div>
                            <div class="flex justify-between font-medium text-lg">
                                <span class="text-white">Total</span>
                                <span class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <!-- Bank Mandiri Card -->
                        <div class="card bg-base-100 shadow-xl mt-6">
                            <div class="card-body">
                                <h2 class="card-title text-xl mb-4 text-white">Pembayaran Bank Mandiri</h2>

                                <!-- Bank Account Details -->
                                <div class="bg-primary p-4 rounded-lg">
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-white">Nomor Rekening</span>
                                        <span class="font-semibold text-black">123-456-7890</span>
                                    </div>
                                    <div class="flex justify-between pt-2">
                                        <span class="text-white">Nama Pemilik Rekening</span>
                                        <span class="font-semibold text-black">PT Mandiri Sejahtera</span>
                                    </div>
                                </div>

                                <!-- Upload Transaction Image -->
                                @csrf
                                <form wire:submit.prevent="saveProof" class="mt-4 space-y-3">
                                    <label class="block text-sm font-medium text-white">Upload Bukti Pembayaran</label>
                                    <div class="relative w-full">
                                        <input type="file" wire:model="proof" accept="image/*"
                                            class="block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-primary focus:border-primary cursor-pointer">
                                        @error('proof') 
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="w-full py-2 px-4 bg-primary text-white font-medium rounded-lg shadow-md hover:bg-primary/80 transition">
                                        Upload Bukti Pembayaran
                                    </button>
                                </form>
                            </div>
                        </div>


                        <!-- Checkout Button -->
                        <button 
                            wire:click="processPayment"
                            class="btn btn-primary w-full mt-6 text-white"
                        >
                            Bayar Sekarang
                        </button>

                        <p class="text-xs text-base-content/70 text-center mt-4 text-white">
                            Dengan melanjutkan pembayaran, kamu menyetujui Syarat & Ketentuan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Address Modal -->
   <!-- Updated Modal with wire:model and x-data -->
    <div class="modal {{ $showAddressModal ? 'modal-open' : '' }}">
        <div class="modal-box">
            <h3 class="text-white font-bold text-lg mb-4">Ganti Alamat</h3>
            
            <form wire:submit.prevent="saveAddress">
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text text-white">Alamat</span>
                    </label>
                    <input type="text" 
                        class="input input-bordered w-full text-white" 
                        wire:model.defer="address"
                        placeholder="Masukkan alamat"
                        required>
                    @error('address') 
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text text-white">Kota</span>
                    </label>
                    <input type="text" 
                        class="input input-bordered w-full text-white" 
                        wire:model.defer="city"
                        placeholder="Masukkan kota"
                        required>
                    @error('city') 
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text text-white">Nomor Telepon</span>
                    </label>
                    <input type="text" 
                        class="input input-bordered w-full text-white" 
                        wire:model.defer="phone_number"
                        placeholder="Masukkan nomor telepon"
                        required>
                    @error('phone_number') 
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost text-white" wire:click="closeModal">Tutup</button>
                    <button type="submit" class="btn btn-primary text-white">Simpan</button>
                </div>
            </form>
        </div>
        
        <!-- Backdrop -->
        <div class="modal-backdrop" wire:click="closeModal"></div>
    </div>
    
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof Livewire !== "undefined") {
            Livewire.on("swal", (data) => {
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    confirmButtonText: "OK"
                });
            });
        } 
    });
</script>
</div>
