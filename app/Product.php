<?php

namespace App;

use App\Traits\WithMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use WithMedia;

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
     * @return string
     */
    protected function getMediaFolder(): string
    {
        return Str::slug($this->category->name, '_');
    }

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function overviews()
    {
        return $this->hasMany(ProductOverview::class);
    }
}
