@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Products</div>

                            <div class="align-self-center text-right">
                                <a href="{{route('products.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add product</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="GET" action="{{ route('products.index') }}" id="filters"></form>

                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 100px;">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Category</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Manufacturer ID</th>
                                <th scope="col" style="width: 120px;"></th>
                            </tr>

                            <tr>
                                <th scope="col">
                                    <input value="{{request('id', old('id'))}}" type="text" name="id" form="filters"
                                           class="form-control"/>
                                </th>
                                <th scope="col">
                                    <input value="{{request('name', old('name'))}}" type="text" name="name"
                                           form="filters" class="form-control"/>
                                </th>
                                <th scope="col">
                                    <select class="form-control" name="category" form="filters">
                                        <option></option>
                                        @foreach(\App\Category::find(1)->get() as $category)
                                            <option value="{{$category->name}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th scope="col">
                                    <select class="form-control" name="brand" form="filters">
                                        <option></option>
                                        @foreach(\App\Brand::find(1)->get() as $brand)
                                            <option value="{{$brand->name}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th scope="col">
                                    <input type="text" class="form-control"/>
                                </th>
                                <th scope="col">
                                    <button class="btn btn-primary" type="submit" form="filters"><i class="fa fa-search"></i></button>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{$product->id}}</td>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->category->name}}</td>
                                    <td>{{$product->brand->name}}</td>
                                    <td>-</td>
                                    <td class="text-right">
                                        <a href="{{route('products.edit', [$product])}}" class="inline btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        <form method="POST" hidden action="{{route('products.destroy', [$product])}}" id="delete">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button class="inline btn btn-sm btn-danger" type="submit" form="delete"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-secondary" role="alert">
                                    No products found.
                                </div>
                            @endforelse
                            </tbody>
                        </table>

                        @if(!empty($products) && ($products instanceof \Illuminate\Pagination\LengthAwarePaginator))
                            {{$products->render()}}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
