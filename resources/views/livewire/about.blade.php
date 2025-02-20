<div>
    @livewire('partials.navbar')
    <div>
        @livewire('partials.navbar')
        <div class="min-h-screen bg-gradient-to-b from-teal-900 via-teal-800 to-teal-700 text-white">
            <div class="container mx-auto px-6 py-12">
                <!-- About Section -->
                <div class="bg-teal-800 text-white p-8 shadow-lg">
                    <h2 class="text-3xl font-bold text-center">Tentang Kami</h2>
                    <p class="text-center mt-4 leading-relaxed">
                        <strong>Nufaisyastore</strong> adalah UMKM yang menyediakan koleksi <strong>gamis, tas, dan kacamata</strong>
                        berkualitas tinggi yang sesuai dengan gaya dan kebutuhan Anda. Kami berkomitmen untuk memberikan
                        produk terbaik dengan harga yang terjangkau.
                    </p>
                </div>
            </div>
            
            <!-- FAQ Section -->
            <div class="container mx-auto px-6 py-12">
                <div class="bg-teal-700 text-white p-8 shadow-lg">
                    <h2 class="text-3xl font-bold text-center">📌 FAQ</h2>
                    <div class="mt-6 space-y-4">
                        <details class="border-b border-white pb-4">
                            <summary class="text-lg font-semibold cursor-pointer">Bagaimana cara melakukan pemesanan?</summary>
                            <p class="text-white mt-2">Anda dapat melakukan pemesanan melalui website kami atau langsung menghubungi admin via WhatsApp.</p>
                        </details>
                        <details class="border-b border-white pb-4">
                            <summary class="text-lg font-semibold cursor-pointer">Apakah ada garansi produk?</summary>
                            <p class="text-white mt-2">Kami memberikan garansi pengembalian produk jika terdapat kerusakan yang bukan dari kesalahan pelanggan.</p>
                        </details>
                    </div>
                </div>
            </div>
        
            <!-- Payment Options -->
            <div class="container mx-auto px-6 py-12">
                <div class="bg-teal-600 text-white p-8 shadow-lg">
                    <h2 class="text-3xl font-bold text-center">💳 Opsi Pembayaran</h2>
                    <ul class="list-disc list-inside space-y-2 mt-4">
                        <li>Transfer Bank (BCA, Mandiri, BNI, BRI)</li>
                        <li>e-Wallet (GoPay, OVO, Dana, ShopeePay)</li>
                        <li>COD (Cash on Delivery) untuk wilayah tertentu</li>
                    </ul>
                </div>
            </div>
        
            <!-- Get Help -->
            <div class="container mx-auto px-6 py-12">
                <div class="bg-teal-500 text-white p-8 shadow-lg text-center">
                    <h2 class="text-3xl font-bold">📞 Butuh Bantuan?</h2>
                    <p class="mt-4">Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami:</p>
                    <div class="flex justify-center mt-6 space-x-4">
                        <a href="https://wa.me/6281234567890" class="px-4 py-2 bg-green-500 text-white shadow hover:bg-green-600 transition">
                            WhatsApp
                        </a>
                        <a href="mailto:nufaisyastore@gmail.com" class="px-4 py-2 bg-blue-500 text-white shadow hover:bg-blue-600 transition">
                            Email
                        </a>
                    </div>
                </div>
            </div>
        
            <!-- Google Maps -->
            <div class="container mx-auto px-6 py-12">
                <div class="bg-teal-400 text-white p-8 shadow-lg">
                    <h2 class="text-3xl font-bold text-center">📍 Lokasi Kami</h2>
                    <p class="text-center mb-4">Kunjungi toko fisik kami untuk melihat koleksi langsung.</p>
                    <div class="w-full h-96 overflow-hidden">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border:0" 
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.0305374940295!2d110.4260!3d-7.8455!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x1234567890abcdef!2sNufaisyastore!5e0!3m2!1sen!2sid!4v1642877734321" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>    
    </div>       
</div>
