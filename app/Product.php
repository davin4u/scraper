<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->using(ProductAttribute::class)
            ->as('attributeValue')
            ->withPivot(['value']);
    }

    /**
     * @param array $attributes
     */
    public function updateAttributes(array $attributes)
    {
        $syncData = [];

        $models = Attribute::whereIn('attribute_key', array_keys($attributes))->get();

        foreach ($models as $model) {
            if (isset($attributes[$model->attribute_key])) {
                $syncData[$model->id] = ['value' => $attributes[$model->attribute_key]];
            }
        }

        $this->attributes()->sync($syncData);
    }
}
