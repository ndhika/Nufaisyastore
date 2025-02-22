<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\Homepage;
use App\Livewire\Product;
use App\Livewire\About;
use App\Livewire\Cart;
use App\Livewire\DetailProduct;
use App\Livewire\Checkout;
use App\Livewire\Order;
use App\Livewire\OrderHistory;

Route::get('/', Homepage::class)->name('home');
Route::get('/about', About::class)->name('about');

// Halaman yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/product', Product::class)->name('product');
    Route::get('product/{slug}', DetailProduct::class)->name('product.detail');
    Route::get('product/{id}', DetailProduct::class)->name('product.detail');
    Route::get('/order', Order::class)->name('order');
    Route::get('/orderhistory', OrderHistory::class)->name('order.history');
    Route::get('/cart', Cart::class)->name('cart');
    Route::get('/checkout', Checkout::class)->name('checkout');
});

Route::get('/login', Login::class)->name('login');
Route::post('/login', Login::class);
Route::get('/register', Register::class)->name('register');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');
