<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductReviewUpdateRequest;
use App\Product;
use App\ProductReview;
use App\ReviewAuthor;
use Carbon\Carbon;
use App\Repositories\ReviewsRepository;

class ProductReviewsController extends Controller
{
    /**
     * @return string
     */
    public function index()
    {
        $productReviews = ProductReview::orderBy('created_at', 'desc')->paginate(30);
        $total = ProductReview::query()->count();

        return view('reviews.index', compact('productReviews', 'total'));
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
    public function edit(ProductReview $productReview)
    {
        $reviewAuthor = ReviewAuthor::where('id', $productReview->author_id)->first();

        return view('reviews.edit', compact('productReview', 'reviewAuthor'));
    }

    /**
     * @param Product $product
     * @param ProductReview $productReview
     * @param ProductReviewUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ProductReview $productReview, ProductReviewUpdateRequest $request)
    {
        (new ReviewsRepository())->createOrUpdate([
            'id' => $productReview->id,
            'author_id' => $productReview->author_id,
            'published_at' => Carbon::parse($productReview->author_id)->toDate(),
            'title' => $request->get('title'),
            'url' => $request->get('url'),
            'pros' => $request->get('pros'),
            'cons' => $request->get('cons'),
            'body' => $request->get('body'),
            'summary' => $request->get('summary'),
            'bought_at' => Carbon::parse($request->get('bought_at'))->toDate(),
            'product_id' => $request->get('product_id'),
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
    public function destroy(ProductReview $productReview)
    {
        $productReview->delete();

        return redirect(route('products.reviews.index'))->with(['status' => 'Review has been deleted']);
    }
}
