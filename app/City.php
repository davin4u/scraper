<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['country_id', 'name'];
}
