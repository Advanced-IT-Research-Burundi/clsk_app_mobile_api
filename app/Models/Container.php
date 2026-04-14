<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = [
        'name',
        'serial_number',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
