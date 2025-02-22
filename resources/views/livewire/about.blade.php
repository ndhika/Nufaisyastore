<div>
    @livewire('partials.navbar')
    <!-- Hero Section (About) -->
    <div class="hero pt-28 pb-20 lg:py-36 bg-gradient-to-b from-gray-900 via-gray-800 to-teal-700 text-white">
        <div class="hero-content text-center">
            <div class="max-w-5xl">
                <h1 class="text-4xl lg:text-5xl font-bold mb-6">Tentang Kami</h1>
                <p class="mt-6 text-lg leading-relaxed">
                    <strong>Nufaisyastore</strong> adalah UMKM yang menyediakan koleksi <strong>gamis, tas, dan kacamata</strong>
                    berkualitas tinggi yang sesuai dengan gaya dan kebutuhan Anda. Kami berkomitmen untuk memberikan
                    produk terbaik dengan harga yang terjangkau.
                </p>
            </div>
        </div>
    </div>
    
    <!-- FAQ & Payment Section -->
    <div class="container mx-auto px-6 py-12 grid md:grid-cols-2 gap-6">
        <!-- FAQ Section -->
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h2 class="text-3xl font-bold text-black flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-teal-600" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m15.113 3.21l.094.083l5.5 5.5a1 1 0 0 1-1.175 1.59l-3.172 3.171l-1.424 3.797a1 1 0 0 1-.158.277l-.07.08l-1.5 1.5a1 1 0 0 1-1.32.082l-.095-.083L9 16.415l-3.793 3.792a1 1 0 0 1-1.497-1.32l.083-.094L7.585 15l-2.792-2.793a1 1 0 0 1-.083-1.32l.083-.094l1.5-1.5a1 1 0 0 1 .258-.187l.098-.042l3.796-1.425l3.171-3.17a1 1 0 0 1 1.497-1.26z" />
                </svg>
                FAQ
            </h2>
            <div class="mt-7 space-y-4">
                <div class="collapse collapse-arrow bg-gray-100 hover:bg-gray-200 transition">
                    <input type="radio" name="faq" />
                    <div class="collapse-title text-lg font-semibold text-teal-500">Bagaimana cara melakukan pemesanan?</div>
                    <div class="collapse-content">
                        <p class="text-gray-900">Anda dapat melakukan pemesanan melalui website kami atau langsung menghubungi admin via WhatsApp.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow bg-gray-100 hover:bg-gray-200 transition">
                    <input type="radio" name="faq" />
                    <div class="collapse-title text-lg font-semibold text-teal-500">Apakah ada garansi produk?</div>
                    <div class="collapse-content">
                        <p class="text-gray-900">Kami memberikan garansi pengembalian produk jika terdapat kerusakan yang bukan dari kesalahan pelanggan.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Section -->
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h2 class="text-3xl font-bold text-black flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-teal-600" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M32 376a56 56 0 0 0 56 56h336a56 56 0 0 0 56-56V222H32Zm66-76a30 30 0 0 1 30-30h48a30 30 0 0 1 30 30v20a30 30 0 0 1-30 30h-48a30 30 0 0 1-30-30ZM424 80H88a56 56 0 0 0-56 56v26h448v-26a56 56 0 0 0-56-56" />
                </svg>
                Opsi Pembayaran
            </h2>
            <ul class="list-disc list-inside space-y-2 mt-7 text-gray-700">
                <li>Transfer Bank (BCA, Mandiri, BNI, BRI)</li>
                <li>e-Wallet (GoPay, OVO, Dana, ShopeePay)</li>
                <li>COD (Cash on Delivery) untuk wilayah tertentu</li>
            </ul>
        </div>
    </div>
    
    <!-- Call to Action Section -->
    <div class="hero bg-gradient-to-b from-gray-900 via-gray-850 to-gray-800 text-white py-16 lg:py-20">
        <div class="hero-content text-center">
            <div class="max-w-2xl space-y-6 mx-auto">
                <h2 class="text-4xl font-bold">Butuh Bantuan?</h2>
                <p class="mt-4 text-gray-300 text-lg">Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami:</p>
                <div class="flex justify-center mt-6 space-x-4">
                    <a href="#" class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 hover:scale-105 transition">
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
    @livewire('partials.footer')
</div>