<?php

namespace App\Http\Controllers;

use App\SearchStatistic;
use App\Http\Requests\CreateSearchStatisticRequest;
use Illuminate\Http\Request;

class SearchStatisticsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $total = SearchStatistic::query()->count();

        if (!empty($request->get('phrase'))) {
            $statistics = SearchStatistic::query();

            if ($request->get('phrase')) {
                $statistics->where('phrase', 'like', '%' . $request->get('phrase') . '%');
            }

            $statistics = $statistics->paginate(30);
        }
        else {
            $statistics = SearchStatistic::all();
        }

        return view('search_statistics.index', compact('statistics','request', 'total'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('search_statistics.create');
    }

    /**
     * @param CreateSearchStatisticRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateSearchStatisticRequest $request)
    {
        $source = $request->get('source');
        $phrase = $request->get('phrase');
        $lastUpdDate = $request->get('last-upd-date');

        SearchStatistic::create([
            'source' => $source,
            'phrase'  => $phrase,
            'last_update_date' =>$lastUpdDate
        ]);

        return redirect(route('search-statistics.index'))->with(['status' => 'Search phrase has been created.']);
    }

    /**
     * @param SearchStatistic $searchStatistic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SearchStatistic $searchStatistic)
    {
        return view('search_statistics.edit', compact('searchStatistic'));
    }

    /**
     * @param SearchStatistic $searchStatistic
     * @param CreateSearchStatisticRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(SearchStatistic $searchStatistic, CreateSearchStatisticRequest $request)
    {
        $source = $request->get('source');
        $phrase = $request->get('phrase');
        $lastUpdDate = $request->get('last-upd-date');

        $searchStatistic->update([
            'source' => $source,
            'phrase'  => $phrase,
            'last_update_date' =>$lastUpdDate
        ]);

        return redirect(route('search-statistics.index'))->with(['status' => 'Phrase has been updated.']);
    }

    /**
     * @param SearchStatistic $searchStatistic
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(SearchStatistic $searchStatistic)
    {
        $searchStatistic->delete();

        return redirect(route('search-statistics.index'))->with(['status' => 'Phrase has been deleted']);
    }
}
