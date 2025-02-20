<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product as ProductModel;
use Illuminate\Support\Facades\Auth;

class Product extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    public function updated($property)
    {
        if ($property === 'search' || $property === 'category') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $productsQuery = ProductModel::with('sizes');

        // Filter pencarian berdasarkan nama atau kategori
        if ($this->search) {
            $productsQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        // Filter berdasarkan kategori jika ada
        if ($this->category) {
            $productsQuery->where('category', $this->category);
        }

        // Cek apakah query kosong
        $products = $productsQuery->get();
        if ($products->isEmpty()) {
            // Jika kosong, ambil semua produk
            $products = ProductModel::with('sizes')->paginate(10);
        } else {
            // Jika ada hasil, tetap gunakan pagination
            $products = $productsQuery->paginate(10);
        }

        return view('livewire.product', compact('products'))
            ->layout('components.layouts.app');
    }
}
