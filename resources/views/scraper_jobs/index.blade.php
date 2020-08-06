@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header clearfix">
                        <span>Scraping jobs | Total: {{$totalCount}} | Not executed: {{$notExecutedCount}}</span>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="executed" name="executed" form="filter" />
                                <label class="custom-control-label float-right" for="executed">Not executed jobs only</label>
                            </div>
                        </div>
                        <div class="btn-group float-right" role="group">
                            <button type="submit" form="filter" class="btn btn-secondary">Show jobs</button>
                            <a class="btn btn-primary float-right" href="{{ route('scraper-jobs.create') }}">Create</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="GET" action="{{route('scraper-jobs.index')}}" id="filter"></form>
                        <table class="table">
                            <thead>
                            <th>url</th>
                            <th>Completed at</th>
                            <th></th>
                            </thead>
                            <tbody>
                            @foreach($scraperJobs as $scraperJob)
                                <tr>
                                    <td>{{ $scraperJob->url }}</td>
                                    <td>{{ $scraperJob->completed_at }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('scraper-jobs.edit', [$scraperJob]) }}"
                                           class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            @empty($scraperJobs)
                                <tr>
                                    <td colspan="3">No jobs added</td>
                                </tr>
                            @endempty
                            </tbody>
                        </table>
                        @if(!empty($scraperJobs))
                            {{$scraperJobs->render()}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
