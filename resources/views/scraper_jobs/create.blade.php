@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">Create new scraping job</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @include('partials.notifications.errors')

                        <div>
                            <form method="POST" action="{{ route('scraper-jobs.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="url">URL for scraping</label>

                                    <input type="url" name="url" class="form-control" id="url" placeholder="URL for scraping" />

                                    <small class="form-text text-muted">For example: https://www.dns-shop.ru/catalog/17a892f816404e77/noutbuki/</small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch my-4">
                                        <input type="checkbox" class="custom-control-input" id="is_regular" name="is_regular" value="1" />

                                        <label class="custom-control-label" for="is_regular">Regular</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('scraper-jobs.index') }}" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
