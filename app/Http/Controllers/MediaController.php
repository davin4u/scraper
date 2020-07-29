<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function edit($id)
    {
        $product = Product::find($id);

        return view('media.edit', compact('product'));
    }

    public function upload(Product $product)
    {
        return view('media.upload', compact('product'));
    }

    public function update(Product $product, Request $request)
    {
        //
    }

    public function destroy($id, Request $request)
    {
        $product = Product::find($request->get('product_id'));
        $product->deleteFile($id);

        return redirect()->back();
    }
}
