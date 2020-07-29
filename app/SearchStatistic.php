<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SearchStatistic
 * @package App
 */
class SearchStatistic extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['source', 'phrase', 'last_update_date'];
}
