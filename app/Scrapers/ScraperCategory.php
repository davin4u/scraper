<?php

namespace App\Scrapers;

use Illuminate\Database\Eloquent\Model;

class ScraperCategory extends Model
{
    /**
     * @var string
     */
    protected $table = 'scraper_categories';

    /**
     * @var array
     */
    protected $fillable = ['url', 'user_id', 'last_visiting_at'];

    /**
     * @var array
     */
    protected $casts = [
        'last_visiting_at' => 'datetime'
    ];
}
