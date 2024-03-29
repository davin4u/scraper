<?php

namespace App\Http\Controllers;

use App\Scrapers\ScraperCategory;
use Illuminate\Http\Request;

class ScraperCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $total = ScraperCategory::query()->count();

        return view('scraper_categories.index', [
            'categories' => ScraperCategory::all(),
            'total' => $total
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('scraper_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if (! $request->get('url')) {
            return redirect(route('scraper.categories.create'))->with([
                'error' => 'Url required.'
            ]);
        }

        $exists = ScraperCategory::where('url', $request->get('url'))->first();

        if (!is_null($exists)) {
            return redirect(route('scraper.categories.create'))->with([
                'error' => 'Category already exists.'
            ]);
        }

        $category = ScraperCategory::create([
            'url' => $request->get('url'),
            'user_id' => auth()->user()->id
        ]);

        return redirect(route('scraper.categories.index'))->with([
            'status' => 'Category successfully created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param ScraperCategory $scraperCategory
     * @return void
     */
    public function show(ScraperCategory $scraperCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ScraperCategory $scraperCategory
     * @return void
     */
    public function edit(ScraperCategory $scraperCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param ScraperCategory $scraperCategory
     * @return void
     */
    public function update(Request $request, ScraperCategory $scraperCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ScraperCategory $scraperCategory
     * @return void
     */
    public function destroy(ScraperCategory $scraperCategory)
    {
        //
    }
}
