@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class=" col-md-12">
                <div class="card">
                    <div class="card-header clearfix">
                        <span>Scraper jobs</span>
                        <a class="btn btn-primary float-right" href="{{ route('scraper-jobs.create') }}">Create</a></div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                            <th>url</th>
                            <th>Completed at</th>
                            <th></th>
                            </thead>

                            <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>{{ $job->url }}</td>
                                    <td>{{ $job->completed_at }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('scraper-jobs.edit', [$job]) }}" class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            @empty($jobs)
                                <tr>
                                    <td colspan="3">No jobs added</td>
                                </tr>
                            @endempty
                            </tbody>
                        </table>
                        @if(!empty($jobs))
                            {{$jobs->render()}}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

