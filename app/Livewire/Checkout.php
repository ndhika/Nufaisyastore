<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProductTransaction;
use App\Models\PromoCode;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Checkout extends Component
{
    use WithFileUploads;

    public $cartItems = [], $subtotal = 0, $total = 0;
    public $shipping = 10000, $insurance = 5000, $adminFee = 2000, $discount = 0, $tax = 0;
    public $promoCode, $promoCodeStatus = null, $promoCodeMessage = '';
    public $showAddressModal = false, $address, $city, $phone_number, $proof;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->loadCart();
        $this->loadAddress();
        $this->calculateTotals();
    }

    public function loadCart()
    {
        try {
            $userId = Auth::id();
            \Log::info('Loading cart for user ID: ' . $userId);
            
            $this->cartItems = Cart::with(['product', 'size']) // Explicitly load product and size
                ->where('user_id', $userId)
                ->get();
            
            \Log::info('Raw Cart Query: ' . Cart::with(['product', 'size'])->where('user_id', $userId)->toSql());
            \Log::info('Cart items found: ' . $this->cartItems->count());
            
            // Check if products are loaded correctly
            foreach ($this->cartItems as $item) {
                \Log::info('Cart item product:', [
                    'cart_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_loaded' => $item->product ? 'yes' : 'no',
                    'product_details' => $item->product ? $item->product->toArray() : 'null'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in loadCart: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
        }
    }
    
    public function loadAddress()
    {
        $this->address = session('temp_address', Auth::user()->address ?? '');
        $this->city = session('temp_city', Auth::user()->city ?? '');
        $this->phone_number = session('temp_phone_number', Auth::user()->phone_number ?? '');
    }

    public function calculateTotals()
    {
        \Log::info('Calculating totals');
        
        try {
            $this->subtotal = $this->cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
            
            $this->tax = $this->subtotal * 0.12;
            $this->total = max(0, $this->subtotal + $this->tax + $this->shipping + $this->insurance + $this->adminFee - $this->discount);
            
            \Log::info('Totals calculated:', [
                'subtotal' => $this->subtotal,
                'tax' => $this->tax,
                'shipping' => $this->shipping,
                'insurance' => $this->insurance,
                'adminFee' => $this->adminFee,
                'discount' => $this->discount,
                'total' => $this->total
            ]);
        } catch (\Exception $e) {
            \Log::error('Error calculating totals: ' . $e->getMessage());
        }
    }

    public function checkPromoCode()
    {
        $promo = PromoCode::where('code', $this->promoCode)->first();
        
        if ($promo) {
            $this->discount = $promo->discount_amount;
            session(['promo_code' => $this->promoCode, 'discount' => $this->discount]);
            $this->promoCodeStatus = true;
            $this->promoCodeMessage = 'Kode promo berhasil diterapkan!';
        } else {
            $this->discount = 0;
            session()->forget(['promo_code', 'discount']);
            $this->promoCodeStatus = false;
            $this->promoCodeMessage = 'Kode promo tidak valid!';
        }
        
        $this->calculateTotals();
    }

    public function applyPromo()
    {
        // Check if a promo code has been entered
        if (!$this->promoCode) {
            $this->promoCodeStatus = false;
            $this->promoCodeMessage = 'Harap masukkan kode promo!';
            return;
        }

        // Fetch promo details
        $promo = PromoCode::where('code', $this->promoCode)->first();

        if ($promo) {
            // Apply the discount
            $this->discount = $promo->discount_amount;
            session(['promo_code' => $this->promoCode, 'discount' => $this->discount]);

            // Update totals
            $this->calculateTotals();

            // Success message
            $this->promoCodeStatus = true;
            $this->promoCodeMessage = 'Kode promo berhasil diterapkan!';
        } else {
            // Invalid promo code
            $this->discount = 0;
            session()->forget(['promo_code', 'discount']);
            $this->calculateTotals();
            $this->promoCodeStatus = false;
            $this->promoCodeMessage = 'Kode promo tidak valid!';
        }
    }


    public function openModal()
    {
        $this->showAddressModal = true;
    }

    public function closeModal()
    {
        $this->showAddressModal = false;
    }

    public function saveAddress()
    {
        session([
            'temp_address' => $this->address,
            'temp_city' => $this->city,
            'temp_phone_number' => $this->phone_number
        ]);

        $this->dispatch('swal', ['title' => 'Alamat Disimpan!', 'text' => 'Alamat berhasil diperbarui.', 'icon' => 'success']);
        $this->closeModal();
    }

    public function saveProof()
    {
        try {
            \Log::info('Attempting to save proof');
            
            $this->validate(['proof' => 'required|image']);
            
            $path = $this->proof->storeAs('buktipembayaran', 'proof_' . time() . '.' . $this->proof->extension(), 'public');
            
            session(['proof' => $path]);
            
            \Log::info('Proof saved successfully', ['path' => $path]);
            
            $this->dispatch('swal', [
                'title' => 'Sukses!',
                'text' => 'Bukti pembayaran berhasil diunggah.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving proof: ' . $e->getMessage());
            
            $this->dispatch('swal', [
                'title' => 'Error!',
                'text' => 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }
    
    // Tambahkan method helper untuk menghitung total dan discount per item
    private function calculateItemTotal($cartItem)
    {
        $subtotal = $cartItem->quantity * $cartItem->product->price;
        $itemDiscount = $this->calculateItemDiscount($cartItem);
        
        // Distribusikan biaya tambahan secara proporsional
        $itemProportion = $subtotal / $this->subtotal;
        $itemShipping = $this->shipping * $itemProportion;
        $itemInsurance = $this->insurance * $itemProportion;
        $itemAdminFee = $this->adminFee * $itemProportion;
        $itemTax = $this->tax * $itemProportion;

        return $subtotal + $itemTax + $itemShipping + $itemInsurance + $itemAdminFee - $itemDiscount;
    }

    private function calculateItemDiscount($cartItem)
    {
        if ($this->discount <= 0) {
            return 0;
        }
        
        $subtotal = $cartItem->quantity * $cartItem->product->price;
        return ($subtotal / $this->subtotal) * $this->discount;
    }

    public function processPayment()
    {
        try {
            \Log::info('Starting processPayment with data:', [
                'cart_items' => $this->cartItems->toArray(),
                'subtotal' => $this->subtotal,
                'total' => $this->total,
                'phone' => $this->phone_number,
                'city' => $this->city,
                'address' => $this->address,
                'proof' => session('proof')
            ]);
    
            if ($this->cartItems->isEmpty()) {
                \Log::info('Cart is empty');
                $this->dispatch('swal', [
                    'title' => 'Keranjang kosong!', 
                    'text' => 'Tambahkan produk sebelum checkout.', 
                    'icon' => 'error'
                ]);
                return;
            }
    
            if (!session('proof')) {
                \Log::info('No proof uploaded');
                $this->dispatch('swal', [
                    'title' => 'Bukti pembayaran belum diunggah!', 
                    'text' => 'Harap unggah bukti pembayaran sebelum checkout.', 
                    'icon' => 'error'
                ]);
                return;
            }
    
            \Log::info('Starting database transaction');
            DB::beginTransaction();
            try {
                \Log::info('Creating transaction with data:', [
                    'user_id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'subtotal' => $this->subtotal,
                    'total' => $this->total,
                    'quantity' => $this->cartItems->sum('quantity')
                ]);
    
                $transactionData = [
                    'user_id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'phone_number' => $this->phone_number,
                    'city' => $this->city,
                    'address' => $this->address,
                    'sub_total_amount' => $this->subtotal,
                    'grand_total_amount' => $this->total,
                    'promo_code_id' => $this->promoCode ? PromoCode::where('code', $this->promoCode)->value('id') : null,
                    'discount_amount' => $this->discount,
                    'quantity' => $this->cartItems->sum('quantity'),
                    'status' => 'paid',
                    'proof' => session('proof'),
                    'product_id' => json_encode($this->cartItems->pluck('product_id')->toArray())
                ];
    
                \Log::info('Transaction data prepared:', $transactionData);
    
                $transaction = ProductTransaction::create($transactionData);
    
                \Log::info('Transaction created successfully:', [
                    'transaction_id' => $transaction->id
                ]);
    
                \Log::info('Deleting cart items');
                $deletedCount = Cart::where('user_id', Auth::id())->delete();
                \Log::info('Deleted cart items count: ' . $deletedCount);
    
                DB::commit();
                \Log::info('Transaction committed successfully');
                
                session()->forget(['proof', 'promo_code', 'discount']);
                \Log::info('Session cleared');
    
                $this->dispatch('swal', [
                    'title' => 'Sukses!', 
                    'text' => 'Transaksi berhasil diproses.', 
                    'icon' => 'success'
                ]);
    
                \Log::info('Redirecting to orders.index');
                return redirect()->route('order');
                
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Transaction error: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                
                $this->dispatch('swal', [
                    'title' => 'Error!', 
                    'text' => 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage(), 
                    'icon' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $this->dispatch('swal', [
                'title' => 'Error!', 
                'text' => 'Terjadi kesalahan saat checkout: ' . $e->getMessage(), 
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.checkout')->layout('components.layouts.app');
    }
}
