@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Reviews | {{$product->name}}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <div>
                            @forelse($productReviews as $review)
                                <div class="card mb-2">
                                    <div class="card-header">
                                        <div class="d-flex">
                                            <div class="align-self-center flex-grow-1">
                                                [{{$review->rating}}] {{$review->title}}
                                            </div>
                                            <div class="align-self-center text-right">
                                                <a href="{{route('products.reviews.edit', [$review])}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{$review->body}}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-secondary" role="alert">
                                    No reviews added yet.
                                </div>
                            @endforelse
                        </div>
                        @if(!empty($productReviews))
                            {{$productReviews->render()}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
