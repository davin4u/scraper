<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'map'];

    /**
     * @var array
     */
    protected $casts = [
        'map' => 'array'
    ];

    /**
     * @param string $sep
     * @return string
     */
    public function mapAsString($sep = '|')
    {
        if (!$this->map) {
            return '';
        }

        return implode($sep, $this->map);
    }
}
