<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
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
        'attribute_key'
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
