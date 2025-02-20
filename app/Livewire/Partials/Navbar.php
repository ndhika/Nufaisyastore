<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public $cartCount;

    public function mount()
    {
        $this->cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->distinct('product_id')->count('product_id') : 0;
    }

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function updateCartCount()
    {
        // Hitung total item dalam cart berdasarkan jumlah produk
        $this->cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
