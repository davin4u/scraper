@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Edit Location | {{$domain->name}} | {{$storeLocation->location_name}}</div>
                            <div class="align-self-center text-right">
                                <a href="{{route('domains.index')}}" class="btn btn-danger">Cancel</a>
                                <button form="edit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST" action="{{route('store-locations.update', [$domain, $store, $storeLocation])}}" id="edit">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="location_name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="location_name" id="location_name" value="{{old('location_name', $storeLocation->location_name)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="address" id="address" value="{{old('address', $storeLocation->address)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="latitude" class="col-sm-3 col-form-label">Latitude</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="latitude" id="latitude" value="{{old('latitude', $storeLocation->latitude)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="longitude" class="col-sm-3 col-form-label">Longitude</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="longitude" id="longitude" value="{{old('longitude', $storeLocation->longitude)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="phone" id="phone" value="{{old('phone', $storeLocation->phone)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="location_description" class="col-sm-3 col-form-label">Description</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" name="location_description" form="edit">{{old('location_description', $storeLocation->location_description)}}</textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
