@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Create Store | {{$domain->name}}</div>

                            <div class="align-self-center text-right">
                                <a href="" class="btn btn-primary"><i class="fa fa-plus"></i> Add Location</a>
                                <a href="{{route('domains.index')}}" class="btn btn-danger">Cancel</a>
                                <a href="" class="btn btn-primary"><i class="fa fa-save"></i> Save</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group row">
                                <label for="platform" class="col-sm-3 col-form-label">Country</label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="platform">
                                        <option>Россия</option>
                                        <option>Germany</option>
                                        <option>Poland</option>
                                        <option>Belgium</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="platform" class="col-sm-3 col-form-label">City</label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="platform">
                                        <option>Москва</option>
                                        <option>Санкт-Петербург</option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <h3>Domain Locations:</h3>

                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 100px;">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Address</th>
                                <th scope="col" style="width: 120px;"></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Магазин на Новокузнецкой</td>
                                <td>Ул. Новокузнецкая, 6</td>
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
