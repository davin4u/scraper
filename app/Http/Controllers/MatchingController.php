<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Http\Resources\ProductsCollection;
use App\Http\Resources\StoreProducts;
use App\Repositories\ProductsRepository;
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
        if ($this->request->has('domain')){
            $domainId = $this->request->get('domain');
            $storeProducts = StoreProduct::query()->where('product_id', 0)
                ->whereHas('store', function ($query) use ($domainId) {
                    $query->where('domain_id', $domainId);
                })->get();

            return new StoreProducts($storeProducts);
        }

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
        $products = (new ProductsRepository())->search([
            'id'          => $this->request->get('id'),
            'name'        => $this->request->get('name'),
            'category_id' => $this->request->get('category_id'),
            'brand_id'    => $this->request->get('brand_id'),
            'page'        => $this->request->get('page', 1),
            'per_page'    => $this->request->get('per_page', 30)
        ], ['category', 'brand']);

        return new ProductsCollection($products);
    }

    public function match()
    {
        $product_id = $this->request->get('product_id');
        $store_product_id = $this->request->get('store_product_id');

        StoreProduct::query()->where('id', $store_product_id)->update(['product_id' => $product_id]);

        return ['success' => true];
    }
}
