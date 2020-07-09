<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ScraperJob
 * @package App
 */
class ScraperJob extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'url', 'completed_at'];
}
