@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header clearfix">
                        <span>Products</span>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="GET" action="{{ route('products.index') }}" id="filters"></form>

                        <table class="table">
                            <thead>
                                <th>
                                    <input value="{{ request('id', old('id')) }}" type="text" name="id" form="filters" class="form-control" placeholder="ID" />
                                </th>

                                <th>
                                    <input value="{{ request('domain', old('domain')) }}" type="text" name="domain" form="filters" class="form-control" placeholder="Domain" />
                                </th>

                                <th>
                                    <input value="{{ request('category', old('category')) }}" type="text" name="category" form="filters" class="form-control" placeholder="Category" />
                                </th>

                                <th>
                                    <input value="{{ request('brand', old('brand')) }}" type="text" name="brand" form="filters" class="form-control" placeholder="Brand" />
                                </th>

                                <th>
                                    <input value="{{ request('name', old('name')) }}" type="text" name="name" form="filters" class="form-control" placeholder="Name" />
                                </th>

                                <th>
                                    <input value="{{ request('sku', old('sku')) }}" type="text" name="sku" form="filters" class="form-control" placeholder="SKU" />
                                </th>

                                <th>
                                    <button class="btn btn-primary" type="submit" form="filters">Filter</button>
                                </th>
                            </thead>

                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->domain->name }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>{{ $product->brand->name }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            <a href="{{ route('products.edit', [$product]) }}" class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @empty($products)
                                    <tr><td colspan="3">No products added</td></tr>
                                @endempty
                            </tbody>
                        </table>

                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
