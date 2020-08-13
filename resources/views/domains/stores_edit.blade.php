@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Edit Store | {{$domain->name}} | #{{$store->id}}</div>

                            <div class="align-self-center text-right">
                                <a href="{{route('store-locations.create', [$domain, $store])}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Location</a>
                                <a href="{{route('domains.index')}}" class="btn btn-danger">Cancel</a>
                                <button form="update" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST" id="update" action="{{route('stores.update', [$domain, $store])}}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="country" class="col-sm-3 col-form-label">Country</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="country_id">
                                        @foreach(\App\Country::all() as $country)
                                            <option value="{{$country->id}}" @if($store->country_id == $country->id) selected @endif>{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="city" class="col-sm-3 col-form-label">City</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="city_id">
                                        @foreach(\App\City::all() as $city)
                                            <option value="{{$city->id}}" @if($store->city_id == $city->id) selected @endif>{{$city->name}}</option>
                                        @endforeach
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
                            @forelse($store->locations as $storeLocation)
                            <tr>
                                <td>{{$storeLocation->id}}</td>
                                <td>{{$storeLocation->location_name}}</td>
                                <td>{{$storeLocation->address}}</td>
                                <td class="text-right">
                                    <a href="{{route('store-locations.edit', [$domain, $store, $storeLocation])}}" class="inline btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <form method="POST" action="{{route('store-locations.destroy', [$domain, $store, $storeLocation])}}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline btn btn-sm btn-danger" type="submit" ><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-secondary" role="alert">
                                    No stores locations found.
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
