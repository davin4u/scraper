<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductReviewUpdateRequest;
use App\Product;
use App\ProductReview;
use Carbon\Carbon;

class ProductReviewsController extends Controller
{
    /**
     * @return string
     */
    public function index()
    {
        return view('reviews.index', ['productReviews' => ProductReview::orderBy('created_at', 'desc')->paginate(30)]);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Product $product)
    {
        $productReviews = ProductReview::where('product_id', $product->id)->orderBy('created_at', 'desc')->paginate(30);

        return view('reviews.show', compact('product', 'productReviews'));
    }

    /**
     * @param Product $product
     * @param ProductReview $productReview
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product, ProductReview $productReview)
    {
        return view('reviews.edit', compact('product', 'productReview'));
    }

    /**
     * @param Product $product
     * @param ProductReview $productReview
     * @param ProductReviewUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Product $product, ProductReview $productReview, ProductReviewUpdateRequest $request)
    {
        $productReview->update([
            'title' => $request->get('title'),
            'url' => $request->get('url'),
            'pros' => $request->get('pros'),
            'cons' => $request->get('cons'),
            'body' => $request->get('body'),
            'summary' => $request->get('summary'),
            'bought_at' => Carbon::parse($request->get('bought_at'))->toDate(),
            'rating' => $request->get('rating'),
            'i_recommend' => $request->has('i_recommend') ? 1 : 0
        ]);

        return redirect(route('products.reviews.index'))->with(['status' => 'Review has been updated']);
    }

    /**
     * @param Product $product
     * @param ProductReview $productReview
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Product $product, ProductReview $productReview)
    {
        $productReview->delete();

        return redirect(route('products.reviews.index'))->with(['status' => 'Review has been deleted']);
    }
}
