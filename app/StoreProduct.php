<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreProduct
 * @package App
 */
class StoreProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['store_id', 'product_id', 'yml_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(StoreProductDetails::class);
    }

    /**
     * @param array $data
     */
    public function updateDetails(array $data): void
    {
        $details = $this->details()->orderByDesc('created_at')->first();

        if (is_null($details)) {
            $this->details()->create($data);

            return;
        }

        foreach ($data as $prop => $value) {
            if ($prop === 'description') {
                continue;
            }

            if ($prop === 'yml_id') {
                continue;
            }

            if ($details->{$prop} !== $value) {
                if ($prop == 'old_price') {
                    $this->details()->update([
                        'old_price' => $value,
                    ]);
                } else {
                    $this->details()->create($data);
                }

                return;
            }
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
