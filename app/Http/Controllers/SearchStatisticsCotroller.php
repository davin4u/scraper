<?php

namespace App\Http\Controllers;

use App\SearchStatistic;
use App\Http\Requests\CreateSearchStatisticRequest;
use Illuminate\Http\Request;

class SearchStatisticsCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!empty($request->get('phrase'))){

            $statistics = SearchStatistic::query();

            if ($request->get('phrase')) {
                $statistics->where('phrase', 'like', '%' . $request->get('phrase') . '%');
            }

            $statistics = $statistics->paginate(30);
        }
        else
        {
            $statistics = SearchStatistic::all();
        }

        return view('search_statistics.index', compact('statistics','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('search_statistics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSearchStatisticRequest $request)
    {
        $source = $request->get('source');
        $phrase = $request->get('phrase');
        $lastUpdDate = $request->get('last-upd-date');

        $statistic = SearchStatistic::create([
            'source' => $source,
            'phrase'  => $phrase,
            'last_update_date' =>$lastUpdDate
        ]);

        return redirect(route('search-statistics.index'))->with(['status' => 'Search phrase has been created.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SearchStatistic $searchStatistic)
    {
        return view('search_statistics.edit', compact('searchStatistic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SearchStatistic $searchStatistic)
    {
        $searchStatistic->delete();

        return redirect(route('search-statistics.index'))->with(['status' => 'Phrase has been deleted']);
    }
}
