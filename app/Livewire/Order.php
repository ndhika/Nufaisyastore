<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductTransaction as OrderModel;
use Illuminate\Support\Facades\Auth;

class Order extends Component
{
    public $shipping = 5000;
    public $total_price;
    public $tax;
    public $insurance = 5000; 
    public $adminfee = 2000; 
    public $orderUser;
    public $discount;

    public function mount()
    {
        $this->orderUser = OrderModel::where('user_id', Auth::id())
            ->whereIn('status', ['unpaid', 'paid', 'processing', 'shipping', 'arrived', 'waiting_confirmation', 'done'])
            ->latest()
            ->first();
    
        // Jika orderUser null, redirect ke halaman produk
        if (!$this->orderUser) {
            return redirect()->route('product'); // Sesuaikan dengan rute produk kamu
        }
    
        // Ambil langsung dari database tanpa menghitung ulang
        $this->total_price = $this->orderUser->grand_total_amount;
        $this->tax = $this->orderUser->sub_total_amount * 0.12;        
        $this->discount = $this->orderUser->discount_amount; 
    }
    
    

    public function getOrderJson()
    {
        return response()->json([
            'order' => $this->orderUser ? [
                'id' => $this->orderUser->id,
                'user_id' => $this->orderUser->user_id,
                'status' => $this->orderUser->status,
                'status_text' => $this->getStatusText(),
                'products' => collect(json_decode($this->orderUser->product_id))->map(function ($productId) {
                    $product = \App\Models\Product::find($productId);
                    return $product ? [
                        'name' => $product->name,
                        'price' => $product->price,
                        'thumbnail' => $product->thumbnail,
                        'sizes' => $product->sizes->pluck('size')->toArray(), // Ambil semua ukuran
                        ] : null;
                })->filter()->values(),
            ] : null
        ]);
    }

    
    public function processPayment()
    {
        if ($this->orderUser) {
            $this->orderUser->update(['status' => 'paid']);

            session()->flash('success', 'Pembayaran berhasil, pesanan telah dikonfirmasi!');
            return redirect()->route('order');
        }
    }

    public function markAsDone()
    {
        if ($this->orderUser && $this->orderUser->status == 'waiting_confirmation') {
            $this->orderUser->update(['status' => 'done']);

            session()->flash('success', 'Pesanan telah dikonfirmasi selesai!');
            return redirect()->route('order');
        }
    }

    public function getStatusText()
    {
        $statuses = [
            'unpaid' => 'Belum Membayar',
            'paid' => 'Sudah Membayar',
            'processing' => 'Sedang Dikemas',
            'shipping' => 'Dikirim',
            'arrived' => 'Barang Sampai',
            'waiting_confirmation' => 'Menunggu Konfirmasi User',
            'done' => 'Selesai',
        ];

        return $statuses[$this->orderUser->status] ?? 'Status Tidak Diketahui';
    }
    public function render()
    {
        return view('livewire.order', [
            'order' => $this->orderUser
        ]);
    }
}
