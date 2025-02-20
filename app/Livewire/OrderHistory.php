<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductTransaction as history;
use Illuminate\Support\Facades\Auth;


class OrderHistory extends Component
{   
    public $orders;

    public function mount()
    {
        if (Auth::check()) {
            $this->orders = Auth::user()->productTransactions()->latest()->get();
        } else {
            $this->orders = collect();
        }
    }

    public function confirmOrder($orderId)
    {
        $order = Auth::user()->productTransactions()->find($orderId);
        if ($order && $order->status == 'waiting_confirmation') {
            $order->update(['status' => 'done']);
            session()->flash('message', 'Pesanan telah dikonfirmasi!');
        }
    }
        

    public function render()
    {
        return view('livewire.order-history')->layout('components.layouts.app');;
    }
}
