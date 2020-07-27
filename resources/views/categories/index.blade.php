@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">

                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Categories</div>
                            <div class="align-self-center text-right">
                                <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add category</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form id="category-filter-form" action="{{ route('categories.index') }}" method="GET"></form>
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 100px;">ID</th>
                                    <th scope="col">Name</th>
                                    <th>Map</th>
                                    <th></th>
                                <tr>
                                <tr>
                                    <th scope="col">
                                        <input form="category-filter-form" name="id" type="text" class="form-control" value="{{ $request->id }}" />
                                    </th>
                                    <th scope="col">
                                        <input form="category-filter-form" name="name" type="text" class="form-control" value="{{ $request->name }}"/>
                                    </th>
                                    <th><button form="category-filter-form" type="submit" class="btn btn-primary"> Filter </button></th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->mapAsString() }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('categories.edit', [$category]) }}" class="inline btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            @if (auth()->user()->isAdmin())
                                                <form method="POST" action="{{ route('categories.destroy', [$category]) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE" />

                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($categories->isEmpty())
                            <div class="alert alert-secondary" role="alert">No categories added</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
