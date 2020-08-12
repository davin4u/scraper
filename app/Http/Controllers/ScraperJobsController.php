<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScraperJobStoreUpdateRequest;
use App\ScraperJob;

class ScraperJobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $executed = \request()->get('executed');
        $scraperJobs = ScraperJob::query()->orderBy('id');
        $notExecutedCount = ScraperJob::query()->whereNull('completed_at')->count();

        if (!is_null($executed)) {
            $scraperJobs->whereNull('completed_at');
        }

        $scraperJobs = $scraperJobs->paginate(30);

        return view('scraper_jobs.index')->with([
            'scraperJobs' => $scraperJobs->appends(\request()->except('page')),
            'notExecutedCount' => $notExecutedCount
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('scraper_jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ScraperJobStoreUpdateRequest $request)
    {
        if (!$request->get('url')) {
            return redirect(route('scraper-jobs.create'))->with([
                'error' => 'Url required.'
            ]);
        }

        ScraperJob::create([
            'url' => $request->get('url'),
            'user_id' => auth()->user()->id,
            'regular' => $request->get('is_regular') ? 1 : 0
        ]);

        return redirect(route('scraper-jobs.index'))->with([
            'status' => 'Job successfully created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ScraperJob $scraperJob
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ScraperJob $scraperJob)
    {
        return view('scraper_jobs.edit', compact('scraperJob'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ScraperJob $scraperJob
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(ScraperJob $scraperJob, ScraperJobStoreUpdateRequest $request)
    {
        $scraperJob->update([
            'url' => $request->get('url'),
            'regular' => $request->get('is_regular') ? 1 : 0
        ]);

        return redirect(route('scraper-jobs.index'))->with(['status' => 'Job has been updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
