@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Reviews | Total: {{$total}}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <div>
                            @include('partials.reviews')
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
