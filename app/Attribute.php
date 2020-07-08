<?php

namespace App;

use App\Traits\Matchable;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use Matchable;

    /**
     * @var string
     */
    protected $table = 'attributes';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',
        'attribute_key',
        'map'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'map' => 'array'
    ];

    public function generateUniqueAttributeKey()
    {
        $attrNumber = $this->id - 1;

        do {
            $key = 'attr_' . ++$attrNumber;

            $exists = static::where('attribute_key', $key)->count();
        } while ($exists > 0);

        $this->update([
            'attribute_key' => $key
        ]);
    }
}
