<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Http\Requests\UpdateProductRequest;
use App\Product;
use App\Repositories\ProductsRepository;
use Illuminate\Http\Request;

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
     * @param Product $products
     */
    public function __construct(Request $request, Product $products)
    {
        $this->request = $request;
        $this->products = $products;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $id = $this->request->get('id', null);
        $category = $this->request->get('category', null);
        $brand = $this->request->get('brand', null);
        $name = $this->request->get('name', null);

        $products = Product::paginate(30);

        if (!is_null($id)) {
            $products = $this->products->where('id', $id)->get();
        }
//
        if (!is_null($category)) {
            $categoryId = Category::where('name', $category)->first();
            $products = $this->products->where('category_id', $categoryId->id)->get();
        }

        if (!is_null($brand)) {
            $brandId = Brand::where('name', $brand)->first();
            $products = $this->products->where('brand_id', $brandId->id)->get();
        }

        if (!is_null($name)) {
            $products = $this->products->where('name', 'like', "%{$name}%")->get();
        }

        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {

    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $keys = [];
        $values = [];
        foreach ($product->attributes as $attribute) {
            array_push($keys, $attribute->name);
            array_push($values, $attribute->attributeValue->value());
        }
        $values = preg_replace('/(\s\s)/u', '', $values);
        $attrs = array_combine($keys, $values);

        return view('products.edit',
            [
                'product' => $product,
                'attrs' => $attrs
            ]);
    }

    /**
     * @param Product $product
     * @param UpdateProductRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function update(Product $product, UpdateProductRequest $request)
    {
        //
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect(route('products.index'))->with(['status' => "Product has been deleted"]);
    }
}
