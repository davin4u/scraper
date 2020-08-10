@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Overviews | {{$product->name}} </div>
                            <div class="align-self-center text-right">
                                <a href="{{route('products.overviews.create', ['id' => $product->id])}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add overview</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <div>
                            @include('partials.overviews')
                        </div>
                        @if(!empty($productOverviews))
                            {{$productOverviews->render()}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
