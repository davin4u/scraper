<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /**
     * @return string
     */
    public function getShortBodyAttribute()
    {
        return Str::limit($this->body, 150, '...');
    }
}
