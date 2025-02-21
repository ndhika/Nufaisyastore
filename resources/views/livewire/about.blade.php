<div>
    @livewire('partials.navbar')
    <!-- Hero Section (About) -->
    <div class="hero pt-28 pb-20 lg:py-36 bg-gradient-to-b from-teal-900 via-teal-800 to-teal-700 text-white">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m15.113 3.21l.094.083l5.5 5.5a1 1 0 0 1-1.175 1.59l-3.172 3.171l-1.424 3.797a1 1 0 0 1-.158.277l-.07.08l-1.5 1.5a1 1 0 0 1-1.32.082l-.095-.083L9 16.415l-3.793 3.792a1 1 0 0 1-1.497-1.32l.083-.094L7.585 15l-2.792-2.793a1 1 0 0 1-.083-1.32l.083-.094l1.5-1.5a1 1 0 0 1 .258-.187l.098-.042l3.796-1.425l3.171-3.17a1 1 0 0 1 1.497-1.26z" />
                </svg>
                FAQ
            </h2>
            <div class="mt-7 space-y-4">
                <div class="collapse collapse-arrow bg-gray-100">
                    <input type="radio" name="faq" />
                    <div class="collapse-title text-lg font-semibold text-teal-500">Bagaimana cara melakukan pemesanan?</div>
                    <div class="collapse-content">
                        <p class="text-gray-900">Anda dapat melakukan pemesanan melalui website kami atau langsung menghubungi admin via WhatsApp.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow bg-gray-100">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3" viewBox="0 0 512 512">
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
    <div class="hero bg-teal-500 text-white py-20">
        <div class="hero-content text-center">
            <div class="max-w-2xl space-y-6">
                <h2 class="text-4xl font-bold">Butuh Bantuan?</h2>
                <p class="mt-4 text-base">Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami:</p>
                <div class="flex justify-center mt-6 space-x-4">
                    <a href="#" class="btn btn-success text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2c-5.46 0-9.91 4.45-9.91 9.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91c0-2.65-1.03-5.14-2.9-7.01m-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18l-3.12.82l.83-3.04l-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24c2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c.02 4.54-3.68 8.23-8.22 8.23m4.52-6.16c-.25-.12-1.47-.72-1.69-.81c-.23-.08-.39-.12-.56.12c-.17.25-.64.81-.78.97c-.14.17-.29.19-.54.06c-.25-.12-1.05-.39-1.99-1.23c-.74-.66-1.23-1.47-1.38-1.72c-.14-.25-.02-.38.11-.51c.11-.11.25-.29.37-.43s.17-.25.25-.41c.08-.17.04-.31-.02-.43s-.56-1.34-.76-1.84c-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31c-.22.25-.86.85-.86 2.07s.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74c.59.26 1.05.41 1.41.52c.59.19 1.13.16 1.56.1c.48-.07 1.47-.6 1.67-1.18c.21-.58.21-1.07.14-1.18s-.22-.16-.47-.28" />
                        </svg>
                        WhatsApp
                    </a>
                    <a href="#" class="btn btn-primary text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h16q.825 0 1.413.588T22 6v12q0 .825-.587 1.413T20 20zm8-7L4 8v10h16V8zm0-2l8-5H4zM4 8V6v12z" />
                        </svg>
                        Email
                    </a>
                </div>
            </div>
        </div>
    </div>
    @livewire('partials.footer')
</div>