<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductOverviewStoreUpdateRequest;
use App\Product;
use App\ProductOverview;

/**
 * Class ProductOverviewsController
 * @package App\Http\Controllers
 */
class ProductOverviewsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $productOverviews = ProductOverview::orderBy('created_at', 'desc')->paginate(30);

        return view('overviews.index', compact('productOverviews'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('overviews.create');
    }

    /**
     * @param ProductOverviewStoreUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ProductOverviewStoreUpdateRequest $request)
    {
        ProductOverview::create([
            'name' => $request->get('name'),
            'url' => $request->get('url'),
            'product_id' => $request->get('product_id')
        ]);

        return redirect(route('products.overviews.index'))->with(['status' => 'Overview has been created']);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Product $product)
    {
        $productOverviews = $product->overviews()->paginate(30);

        return view('overviews.show', compact('product', 'productOverviews'));
    }

    /**
     * @param ProductOverview $productOverview
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ProductOverview $productOverview)
    {
        return view('overviews.edit', compact('productOverview'));
    }

    /**
     * @param ProductOverview $productOverview
     * @param ProductOverviewStoreUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ProductOverview $productOverview, ProductOverviewStoreUpdateRequest $request)
    {
        $productOverview->update([
            'name' => $request->get('name'),
            'url' => $request->get('url'),
            'product_id' => $request->get('product_id')
        ]);

        return redirect(route('products.overviews.index'))->with(['status' => 'Overview has been updated']);
    }

    /**
     * @param ProductOverview $productOverview
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(ProductOverview $productOverview)
    {
        $productOverview->delete();

        return redirect(route('products.overviews.index'))->with(['status' => 'Overview has been deleted']);
    }
}
