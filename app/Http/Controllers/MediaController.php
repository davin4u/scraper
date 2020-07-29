<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

/**
 * Class MediaController
 * @package App\Http\Controllers
 */
class MediaController extends Controller
{
    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Product $product)
    {
        return view('media.edit', compact('product'));
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload(Product $product)
    {
        return view('media.upload', compact('product'));
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Product $product, Request $request)
    {
        $product->saveFilesFromRequest($request);

        return redirect(route('products.media.index', [$product]));
    }

    /**
     * @param Product $product
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Product $product, $id)
    {
        $product->deleteFile($id);

        return redirect()->back();
    }
}
