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

    public $cartItems = [];
    public $subtotal = 0;
    public $total = 0;
    public $shipping = 10000;
    public $insurance = 5000;
    public $adminFee = 2000;
    public $discount = 0;
    public $tax = 0;
    public $promoCode;
    public $promoCodeStatus = null;
    public $promoCodeMessage = '';
    public $showAddressModal = false;
    public $address;
    public $city;
    public $phone_number;
    public $proof;

    protected $listeners = ['refreshComponent' => '$refresh'];

    /**
     * Initialize the component state
     * Loads cart items, address information, and calculates initial totals
     */
    public function mount()
    {
        $this->loadCart();
        $this->loadAddress();
        $this->calculateTotals();
    }

    /**
     * Load cart items for the authenticated user
     * Retrieves cart items with their associated product and size information
     */
    public function loadCart()
    {
        $userId = Auth::id();
        $this->cartItems = Cart::with(['product', 'size'])
            ->where('user_id', $userId)
            ->get();
    }
    
    /**
     * Load user's address information from session or database
     * Retrieves temporary address data from session or falls back to user's saved address
     */
    public function loadAddress()
    {
        $this->address = session('temp_address', Auth::user()->address ?? '');
        $this->city = session('temp_city', Auth::user()->city ?? '');
        $this->phone_number = session('temp_phone_number', Auth::user()->phone_number ?? '');
    }

    /**
     * Calculate order totals including subtotal, tax, and final total
     * Computes all costs and applies any available discounts
     */
    public function calculateTotals()
    {
        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        
        $this->tax = $this->subtotal * 0.12;
        $this->total = max(0, $this->subtotal + $this->tax + $this->shipping + $this->insurance + $this->adminFee - $this->discount);
    }

    /**
     * Check and validate promo code
     * Verifies if the entered promo code exists and applies the discount
     */
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

    /**
     * Apply promo code to the order
     * Validates and applies the promo code, updating the order total
     */
    public function applyPromo()
    {
        if (!$this->promoCode) {
            $this->promoCodeStatus = false;
            $this->promoCodeMessage = 'Harap masukkan kode promo!';
            return;
        }

        $promo = PromoCode::where('code', $this->promoCode)->first();

        if ($promo) {
            $this->discount = $promo->discount_amount;
            session(['promo_code' => $this->promoCode, 'discount' => $this->discount]);
            $this->calculateTotals();
            $this->promoCodeStatus = true;
            $this->promoCodeMessage = 'Kode promo berhasil diterapkan!';
        } else {
            $this->discount = 0;
            session()->forget(['promo_code', 'discount']);
            $this->calculateTotals();
            $this->promoCodeStatus = false;
            $this->promoCodeMessage = 'Kode promo tidak valid!';
        }
    }

    /**
     * Open the address modal
     * Shows the modal for editing shipping address
     */
    public function openModal()
    {
        $this->showAddressModal = true;
    }

    /**
     * Close the address modal
     * Hides the address editing modal
     */
    public function closeModal()
    {
        $this->showAddressModal = false;
    }

    /**
     * Save the shipping address
     * Stores the address information in session and shows success message
     */
    public function saveAddress()
    {
        session([
            'temp_address' => $this->address,
            'temp_city' => $this->city,
            'temp_phone_number' => $this->phone_number
        ]);

        $this->dispatch('swal', [
            'title' => 'Alamat Disimpan!', 
            'text' => 'Alamat berhasil diperbarui.', 
            'icon' => 'success'
        ]);
        $this->closeModal();
    }

    /**
     * Save payment proof image
     * Validates and stores the uploaded payment proof image
     */
    public function saveProof()
    {
        $this->validate(['proof' => 'required|image']);
        
        $path = $this->proof->storeAs(
            'buktipembayaran', 
            'proof_' . time() . '.' . $this->proof->extension(), 
            'public'
        );
        
        session(['proof' => $path]);
        
        $this->dispatch('swal', [
            'title' => 'Sukses!',
            'text' => 'Bukti pembayaran berhasil diunggah.',
            'icon' => 'success'
        ]);
    }
    
    /**
     * Calculate total amount for a single cart item
     * Computes the total including proportional shipping, tax, and other fees
     */
    private function calculateItemTotal($cartItem)
    {
        $subtotal = $cartItem->quantity * $cartItem->product->price;
        $itemDiscount = $this->calculateItemDiscount($cartItem);
        
        $itemProportion = $subtotal / $this->subtotal;
        $itemShipping = $this->shipping * $itemProportion;
        $itemInsurance = $this->insurance * $itemProportion;
        $itemAdminFee = $this->adminFee * $itemProportion;
        $itemTax = $this->tax * $itemProportion;

        return $subtotal + $itemTax + $itemShipping + $itemInsurance + $itemAdminFee - $itemDiscount;
    }

    /**
     * Calculate discount amount for a single cart item
     * Computes the proportional discount amount for an item
     */
    private function calculateItemDiscount($cartItem)
    {
        if ($this->discount <= 0) {
            return 0;
        }
        
        $subtotal = $cartItem->quantity * $cartItem->product->price;
        return ($subtotal / $this->subtotal) * $this->discount;
    }

    /**
     * Process the payment and create transaction
     * Validates cart items and payment proof, creates transaction record,
     * and clears the cart upon successful completion
     */
    public function processPayment()
    {
        if ($this->cartItems->isEmpty()) {
            $this->dispatch('swal', [
                'title' => 'Keranjang kosong!', 
                'text' => 'Tambahkan produk sebelum checkout.', 
                'icon' => 'error'
            ]);
            return;
        }

        if (!session('proof')) {
            $this->dispatch('swal', [
                'title' => 'Bukti pembayaran belum diunggah!', 
                'text' => 'Harap unggah bukti pembayaran sebelum checkout.', 
                'icon' => 'error'
            ]);
            return;
        }

        DB::beginTransaction();
        try {
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

            $transaction = ProductTransaction::create($transactionData);
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();
            
            session()->forget(['proof', 'promo_code', 'discount']);

            $this->dispatch('swal', [
                'title' => 'Sukses!', 
                'text' => 'Transaksi berhasil diproses.', 
                'icon' => 'success'
            ]);

            return redirect()->route('order');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('swal', [
                'title' => 'Error!', 
                'text' => 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage(), 
                'icon' => 'error'
            ]);
        }
    }

    /**
     * Render the checkout component
     * Returns the view for the checkout page
     */
    public function render()
    {
        return view('livewire.checkout')->layout('components.layouts.app');
    }
}