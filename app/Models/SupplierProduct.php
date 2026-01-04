<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_products';

    protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity',
        'purchase_price',
        'date_purchased',
        'note',
    ];

    protected $casts = [
        'date_purchased' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
