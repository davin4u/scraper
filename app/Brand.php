<?php

namespace App;

use App\Traits\Matchable;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Matchable;

    /**
     * @var array
     */
    protected $fillable = ['name', 'map'];

    /**
     * @var array
     */
    protected $casts = [
        'map' => 'array'
    ];
}
