@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit product</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger" role="alert">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif

                        <div>
                            <form method="POST" action="{{ route('products.update', [$product]) }}">
                                <input type="hidden" name="_method" value="PUT" />
                                @csrf

                                <div class="form-group">
                                    <label for="name">Product Name</label>

                                    <input type="text" name="name" class="form-control" id="name" placeholder="Product Name" value="{{ $product->name }}" />

                                    <small class="form-text text-muted"><a href="{{ $product->url }}" target="_blank">{{ $product->url }}</a></small>
                                </div>

                                <div class="form-group">
                                    <label for="sku">Product SKU</label>

                                    <input type="text" name="sku" class="form-control" id="sku" placeholder="Product SKU" value="{{ $product->sku }}" />
                                </div>

                                <div class="form-group">
                                    <label for="domain">Domain</label>

                                    <select name="domain_id" class="form-control" id="domain">
                                        @foreach($domains as $domain)
                                            <option value="{{ $domain->id }}" @if($domain->id === $product->domain_id) selected @endif>{{ $domain->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="category">Category</label>

                                    <select name="category_id" class="form-control" id="category">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if($category->id === $product->category_id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="brand">Brand</label>

                                    <select name="brand_id" class="form-control" id="brand">
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" @if($brand->id === $product->brand_id) selected @endif>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="meta_title">Meta title</label>

                                    <textarea name="meta_title" class="form-control" id="meta_title" placeholder="Meta title">{{ $product->meta_title }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta description</label>

                                    <textarea name="meta_description" class="form-control" id="meta_description" placeholder="Meta description">{{ $product->meta_description }}</textarea>
                                </div>


                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('products.index') }}" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection