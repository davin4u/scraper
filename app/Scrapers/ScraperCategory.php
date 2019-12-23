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
    protected $fillable = ['url', 'user_id', 'scraping_started_at', 'scraping_finished_at'];

    /**
     * @var array
     */
    protected $casts = [
        'scraping_started_at'  => 'datetime',
        'scraping_finished_at' => 'datetime'
    ];
}
