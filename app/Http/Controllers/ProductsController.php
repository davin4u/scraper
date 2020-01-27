<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Domain;
use App\Exceptions\BrandNotFoundException;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\DomainNotFoundException;
use App\Http\Requests\UpdateProductRequest;
use App\Product;
use App\Repositories\ProductsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductsController
 * @package App\Http\Controllers
 */
class ProductsController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ProductsRepository
     */
    protected $products;

    /**
     * ProductsController constructor.
     * @param Request $request
     * @param ProductsRepository $products
     */
    public function __construct(Request $request, ProductsRepository $products)
    {
        $this->request  = $request;
        $this->products = $products;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $page     = $this->request->get('page', 1);
        $perPage  = $this->request->get('per_page', 20);
        $offset   = ($page - 1) * $perPage;

        $id         = $this->request->get('id', null);
        $domain     = $this->request->get('domain', null);
        $category   = $this->request->get('category', null);
        $brand      = $this->request->get('brand', null);
        $name       = $this->request->get('name', null);
        $sku        = $this->request->get('sku', null);

        if (!is_null($domain)) {
            try {
                $this->products->domain($domain);
            }
            catch (DomainNotFoundException $e) {}
        }

        if (!is_null($category)) {
            try {
                $this->products->category($category);
            }
            catch (CategoryNotFoundException $e) {}
        }

        if (!is_null($brand)) {
            try {
                $this->products->brand($brand);
            }
            catch (BrandNotFoundException $e) {}
        }

        if (!is_null($id)) {
            $this->products->where('id', $id);
        }

        if (!is_null($sku)) {
            $this->products->where('sku', $sku);
        }

        if (!is_null($name)) {
            $this->products->whereLike('name', "%$name%");
        }

        $paginator = $this->products->orderBy('id')->offset($offset)->take($perPage)->paginate();

        foreach (['id' => $id, 'domain' => $domain, 'category' => $category, 'brand' => $brand, 'name' => $name, 'sku' => $sku] as $key => $item) {
            if (!is_null($item) && $item) {
                $paginator->appends([$key => $item]);
            }
        }

        return view('products.index', [
            'products' => $paginator
        ]);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $domains    = Domain::all();
        $categories = Category::all();
        $brands     = Brand::all();
        $matches    = $product->matches();

        return view('products.edit', compact('product', 'domains', 'categories', 'brands', 'matches'));
    }

    /**
     * @param Product $product
     * @param UpdateProductRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function update(Product $product, UpdateProductRequest $request)
    {
        $product->update($request->only([
            'name',
            'sku',
            'domain_id',
            'category_id',
            'brand_id',
            'meta_title',
            'meta_description'
        ]));

        $product->saveStorableDocument([
            'attributes' => $request->get('attributes', [])
        ]);

        return redirect(route('products.edit', [$product]))->with([
            'status' => 'Product successfully saved.'
        ]);
    }

    /**
     * @param Product $source
     * @param Product $match
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resolve(Product $source, Product $match)
    {
        return view('products.resolve', compact('source', 'match'));
    }

    /**
     * @param Product $source
     * @param Product $match
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function merge(Product $source, Product $match)
    {
        $source->saveStorableDocument([
            'attributes' => $this->request->get('attributes', [])
        ]);

        if ($match->deleteStorableDocument()) {
            $match->attachStorableDocument($source);

            // @TODO extract this and all related stuff to separate class
            DB::table('product_matches')->where(function ($query) use ($source, $match) {
                return $query->where('product_id', $source->id)->where('possible_match_id', $match->id);
            })->orWhere(function ($query) use ($source, $match) {
                return $query->where('product_id', $match->id)->where('possible_match_id', $source->id);
            })->update(['resolved' => 1]);
        }
        else {
            return redirect(route('products.resolve', [$source, $match]))->withErrors([
                'error' => 'Can not merge the products.'
            ]);
        }

        return redirect(route('products.index'))->with([
            'status' => 'The products were successfully merged.'
        ]);
    }
}
