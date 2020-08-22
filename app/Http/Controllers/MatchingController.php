<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoreProducts;
use App\Product;
use App\StoreProduct;
use Illuminate\Http\Request;

class MatchingController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * MatchingController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return StoreProducts|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if ($this->request->expectsJson()) {
            $storeProducts = StoreProduct::with([
                'store.domain',
                'details'
            ])->where('product_id', 0)->get();

            return new StoreProducts($storeProducts);
        }

        return view('matching_tool.index');
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
