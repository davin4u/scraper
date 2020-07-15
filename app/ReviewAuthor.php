<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReviewAuthor
 * @package App
 */
class ReviewAuthor extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'platform',
        'country_id',
        'city_id',
        'total_reviews',
        'rating'
    ];
}
