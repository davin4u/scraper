<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsCollection;
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

    /**
     * @return ProductsCollection
     */
    public function search()
    {
        $id       = $this->request->get('id');
        $name     = $this->request->get('name');
        $category = $this->request->get('category_id');
        $brand    = $this->request->get('brand_id');

        $products = Product::query()->with(['category', 'brand']);

        if (!is_null($id) && $id) {
            $products->where('id', $id);
        }

        if (!is_null($category) && $category) {
            $products->where('category_id', $category);
        }

        if (!is_null($brand) && $brand) {
            $products->where('brand_id', $brand);
        }

        if (!is_null($name)) {
            $products->where('name', 'like', "%{$name}%");
        }

        $products = $products->get();

        return new ProductsCollection($products);
    }
}
