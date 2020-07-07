<?php

namespace App;

use App\Repositories\ProductAttributesRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use LaravelStorable\Traits\Storable;

class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'manufacturer_id',
        'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
