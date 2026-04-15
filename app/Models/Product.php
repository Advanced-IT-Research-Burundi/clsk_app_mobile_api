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
        'unit_per_package',
        'number_of_cartons',
        'is_archived',
        'container_id',
        'customs_price',
        'cbm',
        'customs_price_currency',
        'total_bif',
        'total_usd',
        'total_rmb',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
        'unit_per_package' => 'integer',
        'number_of_cartons' => 'integer',
        'is_archived' => 'boolean',
        'customs_price' => 'decimal:2',
        'cbm' => 'decimal:4',
        'total_bif' => 'decimal:2',
        'total_usd' => 'decimal:2',
        'total_rmb' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

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

    public function container()
    {
        return $this->belongsTo(Container::class);
    }
}
