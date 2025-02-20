<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProductTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone_number',
        'city',
        'address',
        'grand_total_amount',
        'booking_trx_id',
        'discount_amount',
        'quantity',
        'status',
        'proof',
        'user_id',
        'sub_total_amount',
        'product_id', // Simpan sebagai array JSON
        'promo_code_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productTransaction) {
            if (Auth::check()) {
                $user = Auth::user();
                $productTransaction->user_id = $user->id;
                $productTransaction->name = $user->name;
                $productTransaction->phone_number = $user->phone_number ?? null;
            }
            // Generate random booking_trx_id
        $productTransaction->booking_trx_id = 'Nufa-' . now()->timestamp . '-' . mt_rand(1000, 9999);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getProductsAttribute()
    {
        $productIds = json_decode($this->product_id, true) ?? [];
        
        return Product::whereIn('id', $productIds)->get();
    }

    public function product(): belongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }
}
