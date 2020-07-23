@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">Update scraping job</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @include('partials.notifications.errors')

                        <div>
                            <form method="POST" action="{{ route('scraper-jobs.update', [$scraperJob]) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="url">URL for scraping</label>

                                    <input value="{{$scraperJob->url}}" type="url" name="url" class="form-control" id="url" />
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch my-4">
                                        <input {{ $scraperJob->regular == 1 ? 'checked' : ''}} type="checkbox" class="custom-control-input" id="is_regular" name="is_regular" value="1" />

                                        <label class="custom-control-label" for="is_regular">Regular</label>
                                    </div>
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
