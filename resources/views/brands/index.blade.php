@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header clearfix">
                        <span>Brands</span>
                        <a class="btn btn-primary float-right" href="{{ route('brands.create') }}">Create</a></div>

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
                            @foreach($brands as $brand)
                                <tr>
                                    <td>{{ $brand->id }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->mapAsString() }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('brands.edit', [$brand]) }}" class="btn btn-primary btn-sm">Edit</a>
                                        @if (auth()->user()->isAdmin())
                                            <form method="POST" action="{{ route('brands.destroy', [$brand]) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE" />

                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @empty($brands)
                                <tr><td colspan="3">No brands added</td></tr>
                            @endempty
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
