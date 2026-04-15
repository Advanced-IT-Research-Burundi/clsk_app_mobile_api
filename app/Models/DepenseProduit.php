<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepenseProduit extends Model
{
    protected $table = 'depenses_produits';

    protected $fillable = [
        'product_id',
        'montant',
        'description',
        'currency',
        'rate',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'rate'    => 'decimal:4',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Valeur convertie en BIF
    public function getMontantBifAttribute(): float
    {
        return (float) $this->montant * (float) $this->rate;
    }
}
