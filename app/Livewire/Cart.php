<?php

namespace App\Livewire;

use App\Models\Cart as ModalCart;
use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $cart;
    public $subtotal = 0;
    public $quantities = [];
    public $selectedItems = [];
    public $selectedItemsData = [];

    public function mount()
    {
        $this->loadCart();
    }
    public function toggleSelectAll()
    {
        if ($this->cart && count($this->selectedItems) === $this->cart->count()) {
            $this->selectedItems = []; // Uncheck all
            // Jangan hapus ukuran! Biarkan tetap tersimpan.
        } else {
            $this->selectedItems = $this->cart->pluck('product_id')->toArray(); // Select all
        
            // Jika ukuran sudah ada, jangan reset.
            foreach ($this->cart as $cartItem) {
                if (!isset($this->selectedItemsData[$cartItem->product_id])) {
                    $this->selectedItemsData[$cartItem->product_id] = $cartItem->size ? $cartItem->size->size : 'Tidak ada ukuran';
                }
            }
        }
    }
    
    
    
    
    
    
    public function updatedSelectedItems()
    {
        if (!$this->cart) {
            return;
        }
    
        if (empty($this->selectedItems)) {
            foreach ($this->cart as $cartItem) {
                $this->selectedItemsData[$cartItem->product_id] = $cartItem->size->size ?? 'Tidak ada ukuran';
            }
            return;
        }
    
        foreach ($this->cart as $cartItem) {
            if (in_array($cartItem->product_id, $this->selectedItems)) {
                $this->selectedItemsData[$cartItem->product_id] = $cartItem->size->size ?? 'Tidak ada ukuran';
            }
        }
    }
    


    public function removeSelectedItems()
    {
        if (!$this->cart || empty($this->selectedItems)) {
            return;
        }
    
        ModalCart::where('user_id', Auth::id())->whereIn('product_id', $this->selectedItems)->delete();
        
        foreach ($this->selectedItems as $id) {
            unset($this->selectedItemsData[$id]);
        }
    
        $this->selectedItems = [];
        $this->loadCart();
    }

    public function loadCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        $this->cart = ModalCart::where('user_id', Auth::id())->with(['product', 'size'])->get();
    
        if ($this->cart->isNotEmpty()) {
            foreach ($this->cart as $cartItem) {
                $this->quantities[$cartItem->product_id] = $cartItem->quantity ?? 1;
                $this->selectedItemsData[$cartItem->product_id] = $cartItem->size ?? 'Tidak ada ukuran';
            }
        }
    
        $this->calculateTotal();
    }

    public function updateQuantity($productId, $value)
    {
        $quantity = max(1, (int) $value);
        
        ModalCart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->update(['quantity' => $quantity]);

        $this->quantities[$productId] = $quantity;
        $this->calculateTotal();
    }

    public function removeItem($productId)
    {
        if (!$this->cart) {
            return;
        }
    
        // Jika semua item dipilih (Select All), hapus semua item yang ada di selectedItems
        if (count($this->selectedItems) === $this->cart->count()) {
            ModalCart::where('user_id', Auth::id())->delete();
            $this->selectedItems = []; // Kosongkan selected items
            $this->selectedItemsData = []; // Kosongkan data ukuran
        } else {
            // Hapus hanya item yang dipilih
            ModalCart::where('user_id', Auth::id())->where('product_id', $productId)->delete();
            $this->selectedItems = array_diff($this->selectedItems, [$productId]);
            unset($this->selectedItemsData[$productId]);
        }
    
        $this->loadCart(); // Muat ulang data setelah penghapusan
    }
    


    protected function calculateTotal()
    {
        if (!$this->cart->isNotEmpty()) {
            $this->subtotal = 0;
            return;
        }

        $this->subtotal = $this->cart->sum(function ($cartItem) {
            return ($cartItem->quantity ?? 1) * ($cartItem->product?->price ?? 0);
        });
    }

    public function checkout()
    {
        if (!$this->cart->isNotEmpty()) {
            session()->flash('error', 'Keranjang kosong. Tambahkan produk terlebih dahulu.');
            return;
        }
    
        if ($this->subtotal <= 0) {
            session()->flash('error', 'Terjadi kesalahan pada total harga.');
            return;
        }
        
        return redirect()->route('checkout');
    }
    
    public function render()
    {
        return view('livewire.cart', [
            'cartItems' => $this->cart ?? collect(),
            'subtotal' => $this->subtotal,
        ])->layout('components.layouts.app');
    }
}
