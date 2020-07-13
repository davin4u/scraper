@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header clearfix">
                        <span>Categories</span>
                        <a class="btn btn-primary float-right" href="{{ route('categories.create') }}">Create</a></div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                            <th>Completed at</th>
                            <th>url</th>
                            </thead>

                            <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>{{ $job->completed_at }}</td>
                                    <td>{{ $job->url }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('categories.edit', [$job]) }}" class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            @empty($jobs)
                                <tr><td colspan="3">No jobs added</td></tr>
                            @endempty
                            </tbody>

                        </table>
                            {{$jobs->render()}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

