<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Media
 * @package App
 */
class Media extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'filename',
        'path',
        'full_path',
        'storage',
        'url',
        'type',
        'size',
        'extension'
    ];
}
