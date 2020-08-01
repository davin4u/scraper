<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreLocation
 * @package App
 */
class StoreLocation extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['store_id', 'latitude', 'longitude', 'location_name', 'location_description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
