@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 px-0 px-md-4">
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

                        <a class="btn btn-primary" href="{{ route('categories.index') }}">Product categories</a>

                        <a class="btn btn-primary" href="{{ route('brands.index') }}">Product brands</a>

                        <a class="btn btn-primary" href="{{ route('products.index') }}">Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
