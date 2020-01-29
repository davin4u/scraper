<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductMatch
 * @package App
 */
class ProductMatch extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'possible_match_id',
        'resolved'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotResolved($query)
    {
        return $query->where('resolved', 0);
    }

    /**
     * @param $query
     * @param $productId
     * @return mixed
     */
    public function scopeRelatedTo($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function match()
    {
        return $this->belongsTo(Product::class, 'possible_match_id', 'id');
    }

    /**
     * @param array $data
     * @return mixed
     */
    public static function log(array $data)
    {
        return static::create($data);
    }

    /**
     * @param int $productId
     * @param int $matchId
     */
    public static function resolve(int $productId, int $matchId)
    {
        static::query()->where(function ($query) use ($productId, $matchId) {
            return $query->where('product_id', $productId)->where('possible_match_id', $matchId);
        })->orWhere(function ($query) use ($productId, $matchId) {
            return $query->where('product_id', $matchId)->where('possible_match_id', $productId);
        })->update(['resolved' => 1]);
    }
}
