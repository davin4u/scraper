<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class ProductAttribute
 * @package App
 */
class ProductAttribute extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'product_attributes';

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'attribute_id',
        'value'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}
