<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class Homepage extends Component
{
    public function render()
    {
        $products = Product::where('stock', '>', 0)->where('is_popular', true)->take(3)->get(); 
        return view('livewire.homepage', compact('products'))->layout('components.layouts.app');
    }
}
