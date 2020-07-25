<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $product = Product::find($id);

        return view('media.edit', compact('product'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $product = Product::find($request->get('product_id'));
        $product->deleteFile($id);

        return redirect()->back();
    }
}
