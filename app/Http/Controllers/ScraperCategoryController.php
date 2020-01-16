<?php

namespace App\Http\Controllers;

use App\Scrapers\ScraperCategory;
use Illuminate\Http\Request;

class ScraperCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('scraper_categories.index', [
            'categories' => ScraperCategory::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('scraper_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! $request->get('url')) {
            return redirect(route('scraper_categories.create'))->with([
                'error' => 'Url required.'
            ]);
        }

        $category = ScraperCategory::create([
            'url' => $request->get('url'),
            'user_id' => auth()->user()->id
        ]);

        return redirect(route('scraper_categories.index'))->with([
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
