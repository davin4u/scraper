<?php

namespace App\Observers;

use App\Attribute;
use Illuminate\Support\Facades\Log;

class AttributeObserver
{
    /**
     * @param Attribute $attribute
     */
    public function created(Attribute $attribute)
    {
        $attribute->generateUniqueAttributeKey();
    }
}
