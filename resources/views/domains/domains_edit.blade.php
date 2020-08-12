@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Edit Domain | {{$domain->name}}</div>

                            <div class="align-self-center text-right">
                                <a href="{{route('stores.create', [$domain])}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Store</a>
                                <a href="{{route('domains.index')}}" class="btn btn-danger">Cancel</a>
                                <button form="store" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST" action="{{route('domains.update', [$domain])}}" id="store">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', $domain->name)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="url" class="col-sm-3 col-form-label">Url</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="url" id="url" value="{{old('url', $domain->url)}}">
                                </div>
                            </div>
                        </form>

                        <hr>

                        <h3>Domain stores:</h3>

                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 100px;">ID</th>
                                <th scope="col">Country</th>
                                <th scope="col">City</th>
                                <th scope="col" style="width: 120px;"></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Россия</td>
                                <td>Москва</td>
                                <td class="text-right">
                                    <a href="" class="inline btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="" class="inline btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
