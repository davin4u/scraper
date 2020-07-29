<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateProductRequest;
use App\Product;
use App\Repositories\ProductsRepository;

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

    public function index()
    {
        $id = $this->request->get('id', null);
        $category = $this->request->get('category', null);
        $brand = $this->request->get('brand', null);
        $name = $this->request->get('name', null);

        $products = Product::with('category');

        if (!is_null($id)) {
            $products->where('id', $id);
        }

        if (!is_null($category)) {
            $products->where('category_id', $category);
        }

        if (!is_null($brand)) {
            $products->where('brand_id', $brand);
        }

        if (!is_null($name)) {
            $products->where('name', 'like', "%{$name}%");
        }

        $products = $products->paginate(30);

        return view('products.index')->with([
            'products' => $products->appends(\request()->except('page')),
            'id' => $id,
            'category' => $category,
            'brand' => $brand,
            'name' => $name
        ]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $repository = new ProductsRepository();

        $repository->createOrUpdate([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
        ]);

        return redirect(route('products.index'))->with(['status' => 'Product has been created']);
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Product $product, UpdateProductRequest $request)
    {
        $attributes = $request->get('attributes');
        $repository = new ProductsRepository();

        $repository->createOrUpdate([
            'id' => $request->id,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'attributes' => $attributes
        ]);

        return redirect(route('products.index'))->with(['status' => 'Product has been changed']);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect(route('products.index'))->with(['status' => "Product has been deleted"]);
    }
}
