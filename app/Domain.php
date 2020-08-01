<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Domain
 * @package App
 */
class Domain extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
