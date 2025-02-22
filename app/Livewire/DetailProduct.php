<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetailProduct extends Component
{
    public $product;
    public $selectedImage;
    public $quantity = 1;
    public $selectedSize;
    public $cart;

    protected $listeners = ['cart-updated' => '$refresh'];

    public function mount($slug)
    {
        $this->product = Product::with(['photos', 'sizes'])
            ->where('slug', $slug)
            ->firstOrFail();

        $this->selectedImage = $this->product->thumbnail;
    }

    public function changeImage($image)
    {
        $this->selectedImage = ($this->selectedImage === $image) ? $this->product->thumbnail : $image;
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
            session()->flash('info', 'Jumlah produk diperbarui.');
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            session()->flash('info', 'Jumlah produk diperbarui.');
        }
    }

    public function selectSize($size)
    {
        $this->selectedSize = $size;
        session()->flash('info', 'Ukuran berhasil dipilih.');
    }

    public function addToCart($productId, $size = null, $quantity = 1)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Silakan login terlebih dahulu.');
            return redirect()->route('login');
        }
    
        $product = Product::with('sizes')->find($productId);
    
        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan.');
            return;
        }
    
        // **Pastikan produk memiliki ukuran & pengguna belum memilih ukuran**
        if ($product->sizes->count() > 0 && !$size) {
            session()->flash('error', 'Silakan pilih ukuran terlebih dahulu.');
            return; // **Hentikan eksekusi di sini agar tidak lanjut ke flash sukses**
        }
    
        DB::transaction(function () use ($productId, $size, $quantity, $product) {
            $userId = Auth::id();
            $quantity = max(1, (int) $quantity);
            $size = $size ?? null;
    
            $availableStock = $product->sizes->where('id', $size)->first()->stock ?? $product->stock;
    
            $existingCartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('size_id', $size)
                ->lockForUpdate()
                ->first();
    
            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $quantity;
                if ($newQuantity > $availableStock) {
                    session()->flash('error', 'Total quantity melebihi stok yang tersedia.');
                    return;
                }
                $existingCartItem->increment('quantity', $quantity);
            } else {
                if ($quantity > $availableStock) {
                    session()->flash('error', 'Stok tidak mencukupi.');
                    return;
                }
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'size_id' => $size,
                    'quantity' => $quantity,
                ]);
            }
        });
    
        // **Jika sampai di sini, berarti sukses**
        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang.');
    
        // **Reset jumlah & ukuran setelah sukses**
        $this->reset(['quantity', 'selectedSize']);
    
        // **Perbarui UI**
        $this->dispatch('cart-updated');
    }
    

    public function render()
    {
        $cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;
        
        return view('livewire.detailproduct', [
            'cartCount' => $cartCount
        ])->layout('components.layouts.app');
    }
}
