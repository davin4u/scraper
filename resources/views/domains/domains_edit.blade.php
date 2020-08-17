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
                                <button form="edit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST" action="{{route('domains.update', [$domain])}}" id="edit">
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
                            @forelse($domain->stores as $store)
                            <tr>
                                <td>{{$store->id}}</td>
                                <td>{{\App\Country::query()->select('name')->where('id', $store->country_id)->first()->name}}</td>
                                <td>{{\App\City::query()->select('name')->where('id', $store->city_id)->first()->name}}</td>
                                <td class="text-right">
                                    <a href="{{route('stores.edit', [$domain, $store])}}" class="inline btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <form method="POST" action="{{route('stores.destroy', [$domain, $store])}}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline btn btn-sm btn-danger" type="submit" ><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-secondary" role="alert">
                                    No stores found.
                                </div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
