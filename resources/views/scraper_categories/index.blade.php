@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header clearfix">
                        <span>Scraper categories</span>
                        <a class="btn btn-primary float-right" href="{{ route('scraper.categories.create') }}">Create</a></div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <th>ID</th>
                                <th>Url</th>
                                <th>Processed at</th>
                            </thead>

                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->url }}</td>
                                        <td>{{ $category->scraping_finished_at ? $category->scraping_finished_at->toDateTimeString() : '-' }}</td>
                                    </tr>
                                @endforeach
                                @empty($categories)
                                    <tr><td colspan="2">No categories added</td></tr>
                                @endempty
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
