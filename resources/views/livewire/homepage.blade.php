<div>
    @livewire('partials.navbar')
    <section id="home">
        <div class="hero min-h-screen w-full" style="background-image: url('{{ asset('assets/background.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="hero-content flex-col lg:flex-row-reverse">
                <!-- Carousel Kecil & Otomatis -->
                <div class="w-5/6 relative overflow-hidden">
                    <div class="carousel w-full h-80 relative shadow-xl">
                        <div class="carousel-item w-full absolute inset-0 transition-opacity duration-500 opacity-100">
                            <img src="{{ asset('assets/GambarProduct.jpg') }}" class="w-full h-80 object-cover object-center rounded-lg" />
                        </div>
                        <div class="carousel-item w-full absolute inset-0 transition-opacity duration-500 opacity-0">
                            <img src="{{ asset('assets/gambar1.jpg') }}" class="w-full h-80 object-cover object-center rounded-lg" />
                        </div>
                        <div c
                        lass="carousel-item w-full absolute inset-0 transition-opacity duration-500 opacity-0">
                            <img src="{{ asset('assets/gambar3.jpg') }}" class="w-full h-80 object-cover object-center rounded-lg" />
                        </div>
                    </div>
                </div>
                <div>
                    <h1 class="text-5xl font-bold text-white">Selamat Datang di Nufaisyastore!</h1>
                    <p class="py-6 text-gray-200">
                        Nufaisyastore hadir untuk memenuhi kebutuhan Anda dengan koleksi produk berkualitas, harga bersaing, dan pelayanan terbaik. Dari fashion, aksesoris, hingga kebutuhan sehari-hari semuanya tersedia di sini!
                    </p>
                    <a class="btn btn-primary text-slate-200" href="#product">Cari Produk!</a>
                </div>
            </div>
        </div>
    </section>
    <section id="about" class="relative bg-slate-200 py-16">
    <div class="container mx-auto flex flex-col md:flex-row items-center gap-10 px-6 md:px-16">
        <div class="md:w-1/2 pr-6 md:pr-10 text-left">
            <h2 class="text-4xl font-bold mb-4 text-black">About Us</h2>
            <p class="text-lg leading-relaxed text-slate-800">
                Selamat datang di Nufaisyastore! Kami menyediakan koleksi gamis, tas, dan kacamata berkualitas tinggi 
                yang sesuai dengan gaya dan kebutuhan Anda.  
                Misi kami adalah memberikan pengalaman belanja terbaik dengan produk pilihan yang eksklusif.  
            </p>
            <p class="mt-4 text-lg leading-relaxed text-slate-800">
                Tim kami berkomitmen untuk memberikan pelayanan terbaik dan kepuasan pelanggan.  
                Berbelanja di Nufaisyastore berarti memilih kualitas dan kenyamanan.  
                Jadikan kami tujuan utama Anda untuk mendapatkan produk premium dengan harga terbaik!  
            </p>
        </div>
        <div class="md:w-1/2 place-items-center w-1/5">
            <img src="{{ asset('assets/kolase.png') }}" alt="Product 1" class=" max-w-md object-cover">
        </div>                       
    </div>
    </section>
        <section id="product" class=" bg-gradient-to-b from-gray-500 to-gray-900 py-16">
            <div class="container mx-auto px-6 md:px-16">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-white mb-12">
                    Produk Kami
                </h2>    
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($products as $product)
                        <div class="bg-gray-100 rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-xl font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-gray-700 text-sm border border-gray-400 rounded-xl px-3 py-1">{{ $product->category }}</p>
                                </div>
                                <a href="{{ route('product.detail', $product->slug) }}" 
                                    class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-300 font-medium text-center block">
                                    Tambahkan Ke Keranjang
                                </a>                                
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-12">
                    <a href="{{ route('product') }}" class="inline-block rounded-xl text-white border border-white  px-8 py-3 hover:bg-black hover:text-yellow-500 transition duration-300">
                        Lihat Lebih Banyak
                    </a>
                </div>
            </div>
        </section>
    @livewire('partials.footer')
</div>
