<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Product;
use App\StoreProduct;

class MatchingController extends Controller
{
    public function index()
    {
        $query = StoreProduct::query();
        $products = $query->where('product_id', 0)->paginate(30);
        $domains = Domain::all();

        return view('matching_tool.index')->with([
            'domains' => $domains,
            'products' => $products,
        ]);
    }

    public function search()
    {
        $id = request('id', null);
        $name = request('name', null);
        $domain = request('domain', null);
        $products = Product::query();

        if (!is_null($id)) {
            $products->where('id', $id);
        }

        if (!is_null($name)) {
            $products->where('name', 'like', "%{$name}%");
        }

        $products = $products->get();

        return $products->toJson();
    }
}
