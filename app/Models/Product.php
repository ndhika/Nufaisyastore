<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class Product extends Model
{
    use hasFactory, softDeletes;

    protected $fillable = [
        'name',
        'thumbnail',
        'slug',
        'about',
        'stock',
        'price',
        'is_popular',
        'category',
    ];

    // Otomatis generate slug saat name diisi
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    //bahwa slug adalah route key name
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ProductTransaction::class, 'product_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class, 'product_id');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class, 'product_id');
    }

}
