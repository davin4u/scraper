@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
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
                                <th>ID</th>
                                <th>Name</th>
                                <th>Map</th>
                                <th></th>
                            </thead>

                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->mapAsString() }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('categories.edit', [$category]) }}" class="btn btn-primary btn-sm">Edit</a>
                                            @if (auth()->user()->isAdmin())
                                                <form method="POST" action="{{ route('categories.destroy', [$category]) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE" />

                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @empty($categories)
                                    <tr><td colspan="3">No categories added</td></tr>
                                @endempty
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
