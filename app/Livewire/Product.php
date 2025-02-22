<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product as ProductModel;

class Product extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    public function updated($property)
    {
        if ($property === 'search' || $property === 'category') {
            $this->resetPage();
            session()->flash('message', 'Produk telah difilter!');
        }
    }

    public function render()
    {
        $productsQuery = ProductModel::with('sizes');

        // Jika kategori dipilih, filter berdasarkan kategori
        if (!empty($this->category)) {
            $productsQuery->where('category', $this->category);
        }

        // Jika ada input pencarian, cari berdasarkan kategori yang dipilih (jika ada)
        if (!empty($this->search)) {
            $productsQuery->where('name', 'like', '%' . $this->search . '%');
        }

        // Ambil hasil query
        $products = $productsQuery->paginate(10);

        return view('livewire.product', compact('products'))
            ->layout('components.layouts.app');
    }
}
