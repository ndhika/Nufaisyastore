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
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function selectSize($size)
    {
        $this->selectedSize = $size;
    }

    public function addToCart($productId, $size = null, $quantity = 1)
    {
        if (!Auth::check()) {
            $this->dispatch('notify', [
                'message' => 'Silakan login terlebih dahulu',
                'type' => 'error'
            ]);
            return redirect()->route('login');
        }

        DB::transaction(function () use ($productId, $size, $quantity) {
            $userId = Auth::id();
            $product = Product::with('sizes')->find($productId);

            if (!$product) {
                $this->dispatch('notify', [
                    'message' => 'Produk tidak ditemukan',
                    'type' => 'error'
                ]);
                return;
            }

            if ($product->sizes->count() > 0 && !$size) {
                $this->dispatch('notify', [
                    'message' => 'Silakan pilih ukuran terlebih dahulu',
                    'type' => 'error'
                ]);
                return;
            }

            $quantity = max(1, (int) $quantity);
            $size = $size ?? null;

            $existingCartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('size_id', $size)
                ->lockForUpdate()
                ->first();

            $availableStock = $product->sizes->where('id', $size)->first()->stock ?? $product->stock;

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $quantity;

                if ($newQuantity > $availableStock) {
                    $this->dispatch('notify', [
                        'message' => 'Total quantity melebihi stok yang tersedia',
                        'type' => 'error'
                    ]);
                    return;
                }

                $existingCartItem->increment('quantity', $quantity);
            } else {
                if ($quantity > $availableStock) {
                    $this->dispatch('notify', [
                        'message' => 'Stok tidak mencukupi',
                        'type' => 'error'
                    ]);
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

        // Reset jumlah & ukuran setelah sukses
        $this->reset(['quantity', 'selectedSize']);

        // Perbarui UI
        $this->dispatch('cart-updated');
        $this->dispatch('notify', [
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'type' => 'success'
        ]);
    }


    
    public function render()
    {
        $cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;

        return view('livewire.detailproduct', [
            'cartCount' => $cartCount
        ])->layout('components.layouts.app');
    }
}
