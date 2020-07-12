<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductMedia
 * @package App
 */
class ProductMedia extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_media';

    /**
     * @var array
     */
    protected $fillable = ['product_id', 'media_id'];
}
