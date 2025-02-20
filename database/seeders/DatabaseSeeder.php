<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Dhika',
            'email' => 'dhika@gmail.com',
            'phone_number' => '08123456789', // Ganti dengan nomor yang sesuai
            'slug' => Str::slug('Dhika'),
            'password' => Hash::make('dhika'), // Hashing password untuk keamanan
            'role' => 'admin',
        ]);

        // Contoh gambar yang akan disimpan
        $images = [
            'gambar1.jpg',
            'GambarProduct.jpg',
            'gambar3.jpg',
        ];

        foreach ($images as $image) {
            // Path sumber dari public/assets/
            $sourcePath = public_path('assets/' . $image);
        
            // Path tujuan hanya 'product/{random_name}.jpg'
            $fileName = Str::random(40) . '.jpg';
            $destinationPath = 'product/' . $fileName;
        
            // Salin gambar ke storage/public/product/
            Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));
        
            // Buat produk
            $product = Product::create([
                'name' => 'Contoh Produk ' . Str::title(str_replace('.jpg', '', $image)),
                'thumbnail' => $destinationPath, // Hanya 'product/{nama_file}.jpg'
                'slug' => Str::slug('Contoh Produk ' . str_replace('.jpg', '', $image)),
                'about' => 'Ini adalah deskripsi contoh produk.',
                'stock' => rand(10, 100),
                'price' => 200000,
                'is_popular' => rand(0, 1),
                'category' => ['Tas', 'Kacamata', 'Gamis'][array_rand(['Tas', 'Kacamata', 'Gamis'])],
            ]);
        
            // Tambahkan foto produk
            ProductPhoto::create([
                'product_id' => $product->id,
                'photo' => $destinationPath, // Hanya 'product/{nama_file}.jpg'
            ]);
        
            // Tambahkan ukuran produk
            Size::create([
                'product_id' => $product->id,
                'size' => ['S', 'M', 'L'][array_rand(['S', 'M', 'L'])],
            ]);
        }           
    }
}
