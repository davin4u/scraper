<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Store
 * @package App
 */
class Store extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['country_id', 'city_id', 'domain_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(StoreLocation::class);
    }
}
