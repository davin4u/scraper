@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>
                        <a class="btn btn-primary" href="{{ route('scraper.categories.index') }}">Scraping categories</a>

                        <a class="btn btn-primary" href="{{ route('categories.index') }}">Products categories</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
