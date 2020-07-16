<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductReview
 * @package App
 */
class ProductReview extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'author_id',
        'title',
        'url',
        'published_at',
        'pros',
        'cons',
        'likes_count',
        'body',
        'summary',
        'bought_at',
        'rating',
        'i_recommend'
    ];
}
