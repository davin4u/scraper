<?php

return [
    'tokens' => [
        1 => 'a36af833ad89123aa532cd6664f610b5'
    ],

    'endpoints' => [
        'domains'    => env('API_URL') . '/api/domains',
        'categories' => env('API_URL') . '/api/categories',
        'brands'     => env('API_URL') . '/api/brands',
        'products'   => env('API_URL') . '/api/products',
        'product-attributes' => env('API_URL') . '/api/product-attributes'
    ],
];
