@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">
                                <div>Total offers: {{$total}} | Domain exists: {{$isDomainExists}}</div>
                            </div>
                            <div class="align-self-center flex-grow-1">
                                <a href="{{route('yml-import.index')}}" class="btn btn-primary float-right">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')

                        <form action="{{route('yml-import.import')}}" method="POST" id="extract">
                            @csrf
                            <table class="table">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 100px;">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Import</th>
                                    <th scope="col">Exists</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{$category->getId()}}</td>
                                        <td>{{$category->getName()}}</td>
                                        <td><input type="checkbox" name="selectedCategories[]" value="{{$category->getId()}}"></td>
                                        <td>{{$existsCategories[$category->getId()]}}</td>
                                    </tr>
                                @empty
                                    <div class="alert alert-secondary" role="alert">
                                        No categories found.
                                    </div>
                                @endforelse
                                </tbody>
                            </table>
                            <button type="submit" form="extract" class="btn btn-primary">Import</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
