<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchStatistic extends Model
{
    protected $fillable = ['source', 'phrase', 'last_update_date'];
}
