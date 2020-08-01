@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Authors</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="GET" action="{{ route('authors.index') }}" id="filters"></form>

                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 100px;">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Platform</th>
                                <th scope="col" style="width: 120px;"></th>
                            </tr>

                            <tr>
                                <th scope="col">
                                    <input type="text" value="{{request('id', old('id'))}}" name="id" form="filters" class="form-control"/>
                                </th>
                                <th scope="col">
                                    <input type="text" value="{{request('name', old('name'))}}" name="name" form="filters" class="form-control"/>
                                </th>
                                <th scope="col">
                                    <input type="text" value="{{request('platform', old('platform'))}}" name="platform" form="filters" class="form-control"/>
                                </th>
                                <th scope="col">
                                    <button class="btn btn-primary" type="submit" form="filters"><i class="fa fa-search"></i></button>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($reviewAuthors as $author)
                                <tr>
                                    <td>{{$author->id}}</td>
                                    <td>{{$author->name}}</td>
                                    <td>{{$author->platform}}</td>
                                    <td class="text-right">
                                        <a href="{{route('authors.edit', [$author])}}" class="inline btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        <form method="POST" action="{{route('authors.destroy', [$author])}}">
                                            @csrf()
                                            @method('DELETE')
                                            <button class="inline btn btn-sm btn-danger" type="submit" ><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-secondary" role="alert">
                                    No authors found.
                                </div>
                            @endforelse
                            </tbody>
                        </table>
                        @if(!empty($reviewAuthors))
                            {{$reviewAuthors->render()}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
