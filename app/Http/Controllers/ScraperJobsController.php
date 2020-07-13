<?php

namespace App\Http\Controllers;

use App\ScraperJob;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScraperJobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $jobs = ScraperJob::paginate(30);
        return view('scraper-jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('scraper-jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if (! $request->get('url')) {
            return redirect(route('scraper-jobs.create'))->with([
                'error' => 'Url required.'
            ]);
        }

        $exists = ScraperJob::where('url', $request->get('url'))->first();

        if (!is_null($exists)) {
            return redirect(route('scraper-jobs.create'))->with([
                'error' => 'Job already exists.'
            ]);
        }

        ScraperJob::create([
            'url' => $request->get('url'),
            'user_id' => auth()->user()->id,
            'completed_at' => Carbon::now()->toDateTimeString(),
        ]);

        return redirect(route('scraper-jobs.index'))->with([
            'status' => 'Job successfully created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $job = ScraperJob::findOrFail($id);
        return view('scraper-jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        $job = ScraperJob::findOrFail($id);
        $job->url = $request->get('url');
        $job->save();
        return redirect(route('scraper-jobs.index'))->with(['status' => 'Job has been updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
