<?php

namespace App\Http\Controllers;

use App\ProductMatch;

class ProductMatchesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $matches = ProductMatch::notResolved()->get();

        return view('product_matches.index', compact('matches'));
    }
}
