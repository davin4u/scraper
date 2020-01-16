@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit product category</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger" role="alert">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif

                        <div>
                            <form method="POST" action="{{ route('categories.update', [$category]) }}">
                                <input type="hidden" name="_method" value="PUT" />
                                @csrf

                                <div class="form-group">
                                    <label for="name">Category Name</label>

                                    <input type="text" name="name" class="form-control" id="name" placeholder="Category Name" value="{{ $category->name }}" />
                                </div>

                                <div class="form-group">
                                    <label for="map">Mapping</label>

                                    <input type="text" name="map" class="form-control" id="map" placeholder="Mapping" value="{{ $category->mapAsString(',') }}" />

                                    <small class="form-text text-muted">Comma separated different variants of category name for matching with different stores.</small>
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
