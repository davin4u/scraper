<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'country_id',
        'city_id',
        'name',
        'address',
        'working_hours',
        'working_days'
    ];
}
