<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOverview extends Model
{
    protected $fillable = [
        'name',
        'url',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
