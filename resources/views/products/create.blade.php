@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Create product </div>

                            <div class="align-self-center text-right">
                                <a href="{{route('products.index')}}" class="btn btn-danger">Cancel</a>
                                <a href="" class="btn btn-primary"><i class="fa fa-image"></i> Manage media</a>
                                <a href="" class="btn btn-primary"><i class="fa fa-comments"></i> Reviews</a>
                                <button form="store" type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST" action="{{route('products.store')}}" id="store">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="https://via.placeholder.com/250" class="img-thumbnail img-fluid">
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="category" class="col-sm-3 col-form-label">Category</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="category_id">
                                                @foreach(\App\Category::all() as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="brand" class="col-sm-3 col-form-label">Brand</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="brand_id">
                                                @foreach(\App\Brand::all() as $brand)
                                                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="manufacturer_id" class="col-sm-3 col-form-label">Manufacturer ID</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="manufacturer_id">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="description" form="store">{{old('description')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
