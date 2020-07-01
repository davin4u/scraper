<?php

namespace App;

use App\Repositories\ProductAttributesRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use LaravelStorable\Traits\Storable;

class Product extends Model
{
    use Storable;

    /**
     * @var string
     */
    protected $storableKey = 'storable_id';

    /**
     * @var string
     */
    protected $storableCollection = 'products';

    /**
     * @var array
     */
    protected $fillable = [
        'domain_id',
        'category_id',
        'brand_id',
        'name',
        'sku',
        'manufacturer_id',
        'url',
        'store_rating',
        'votes_count',
        'in_stock',
        'meta_title',
        'meta_description',
        'storable_id',
        'scraped_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pricesHistory()
    {
        return $this->hasMany(ProductPriceHistory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
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
     * @return array
     */
    public function toStorableDocument()
    {
        return [
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id
        ];
    }

    /**
     * @param array $data
     * @return $this
     */
    public function fillStorableDocument(array $data)
    {
        if (!empty($data) && (!is_null($this->storable) || !is_null($this->getStorableDocument()))) {
            foreach ($data as $key => $value) {
                $this->storable->{$key} = $value;
            }
        }

        return $this;
    }

    /**
     * @param Product $product
     */
    public function attachStorableDocument(Product $product)
    {
        $this->update([
            $this->storableKey => $product->{$this->storableKey}
        ]);
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getStorableAttributes()
    {
        if (is_null($this->category_id)) {
            return [];
        }

        if (empty($this->storable)) {
            $this->getStorableDocument();
        }

        /** @var ProductAttributesRepository $attributes */
        $attributes = app()->make(ProductAttributesRepository::class);

        return $attributes->getCategoryAttributes($this->category_id, Arr::get($this->storable, 'attributes', []));
    }
}
