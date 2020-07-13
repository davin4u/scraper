@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Update scraping job</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div>
                            <form method="POST" action="{{ route('scraper-jobs.update', [$job]) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="url">URL for scraping</label>
                                    <input value="{{$job->url}}" type="url" name="url" class="form-control" id="url" />
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('scraper-jobs.index') }}" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
