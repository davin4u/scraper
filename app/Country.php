<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 * @package App
 */
class Country extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'iso_code', 'phone_code'];
}
