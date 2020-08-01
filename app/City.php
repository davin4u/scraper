<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * @package App
 */
class City extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['country_id', 'name'];
}
