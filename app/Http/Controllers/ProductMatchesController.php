<?php

namespace App\Http\Controllers;

use App\ProductMatch;
use Illuminate\Http\Request;

class ProductMatchesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $page    = $request->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        $matches = ProductMatch::notResolved()->offset($offset)->take($perPage)->paginate();

        return view('product_matches.index', compact('matches'));
    }
}
