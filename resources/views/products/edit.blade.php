@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Edit product | #{{$product->id}} {{$product->name}}</div>

                            <div class="align-self-center text-right">
                                <a href="{{route('products.index')}}" class="btn btn-danger">Cancel</a>
                                <button form="edit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>

                            <div class="dropdown show ml-1">
                                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                    <a href="{{route('products.media.index', [$product])}}" class="dropdown-item">Manage media</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{route('products.reviews.show', [$product])}}" class="dropdown-item"> Reviews</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')

                        <form method="POST" action="{{route('products.update', [$product])}}" id="edit">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{$product->media->first()->url ?? 'https://via.placeholder.com/250'}}" class="img-thumbnail img-fluid">
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label for="id" class="col-sm-3 col-form-label">ID</label>
                                        <div class="col-sm-5">
                                            <input type="text" readonly class="form-control-plaintext" id="id" name="id" value="{{$product->id}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="name" name="name" value="{{$product->name}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="category" class="col-sm-3 col-form-label">Category</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="category_id">
                                                @foreach(\App\Category::all() as $category)
                                                    <option value="{{$category->id}}" @if($category->id === $product->category->id) selected @endif >{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="brand" class="col-sm-3 col-form-label">Brand</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="brand_id">
                                                @foreach(\App\Brand::all() as $brand)
                                                    <option value="{{$brand->id}}" @if($brand->id === $product->brand->id) selected @endif >{{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="manufacturer_id" class="col-sm-3 col-form-label">Manufacturer ID</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="manufacturer_id"
                                                   value="-">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="description" form="edit">{{$product->description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4>Attributes</h4>
                            <hr/>
                            <div class="row">
                                <div class="col-md-12">
                                    @foreach($product->attributes as $attribute)
                                        <div class="form-group row">
                                            <label for="{{$attribute->attribute_key}}" class="col-sm-2 col-form-label">{{$attribute->name}}</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="{{$attribute->attribute_key}}" name="attributes[{{$attribute->attribute_key}}]"
                                                       value="{{$attribute->attributeValue->value()}}"/>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(count($product->attributes) == 0)
                                        @foreach(\App\Attribute::where('category_id', $product->category->id)->get() as $attribute)
                                                <div class="form-group row">
                                                    <label for="{{$attribute->attribute_key}}" class="col-sm-2 col-form-label">{{$attribute->name}}</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="{{$attribute->attribute_key}}" name="attributes[{{$attribute->attribute_key}}]"/>
                                                    </div>
                                                </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
