@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 px-0 px-md-4">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex">
                        <div class="align-self-center flex-grow-1">Search Statistics | Total: {{$total}}</div>
                        <div class="align-self-center text-right">
                            <a href="{{ route('search-statistics.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="search-statistics-filter-form" action="{{ route('search-statistics.index') }}" method="GET"></form>
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:100px;">ID</th>
                                <th>Source</th>
                                <th>Phrase</th>
                                <th>Amount</th>
                                <th style="min-width:90px;"></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th scope="col"><input form="search-statistics-filter-form" name="phrase" type="text" class="form-control" value="{{ $request->phrase }}"/></th>
                                <th></th>
                                <th class="text-right"><button form="search-statistics-filter-form" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($statistics as $statistic)
                                <tr>
                                    <td>{{ $statistic->id }}</td>
                                    <td>{{ $statistic->source }}</td>
                                    <td>{{ $statistic->phrase }}</td>
                                    <td>{{ $statistic->amount }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('search-statistics.edit', [$statistic]) }}" class="inline btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        @if (auth()->user()->isAdmin())
                                            <form method="POST" action="{{ route('search-statistics.destroy', [$statistic]) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE" />

                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($statistics->isEmpty())
                        <div class="alert alert-secondary" role="alert">No phrases added</div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
