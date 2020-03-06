<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductMatchesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $page    = $request->get('page', 1);

        $response = api()->index('products', [
            'page' => $page,
            'per_page' => $perPage
        ], 'matches/auto-matches');

        $matches = $response->data();
        $meta = $response->meta();

        $paginator = new LengthAwarePaginator($matches, $meta['total'], $meta['per_page'], $meta['current_page']);

        return view('product_matches.index', compact('matches', 'paginator'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function userMatches(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $page    = $request->get('page', 1);

        $response = api()->index('products', [
            'page' => $page,
            'per_page' => $perPage
        ], 'matches/user-matches');

        $matches = $response->data();
        $meta = $response->meta();

        $paginator = new LengthAwarePaginator($matches, $meta['total'], $meta['per_page'], $meta['current_page']);

        return view('product_matches.index', compact('matches', 'paginator'));
    }
}
