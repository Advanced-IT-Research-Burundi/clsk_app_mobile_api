<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'packaging',
        'exchange_rate',
        'date',
        'user_id',
        'supplier_id',
        'category_id',
        'devise_id',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class);
    }
}
