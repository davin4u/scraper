@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create new scraping category</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div>
                            <form method="POST" action="{{ route('categories.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="url">Category URL</label>

                                    <input type="url" name="url" class="form-control" id="url" placeholder="Category URL" />

                                    <small class="form-text text-muted">For example: https://www.dns-shop.ru/catalog/17a892f816404e77/noutbuki/</small>
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('categories.index') }}" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
