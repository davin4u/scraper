@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Manage media |
                                #{{$product->id}} {{$product->name}}</div>

                            <div class="align-self-center text-right">
                                <a href="{{\Illuminate\Support\Facades\URL::previous()}}"
                                   class="btn btn-danger">Cancel</a>
                                <a href="" class="btn btn-primary"><i class="fa fa-save"></i> Save</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST">
                            @csrf
                            @method('PUT')
                            <div class="d-flex flex-wrap">
                                @foreach($product->media as $media)
                                    <div class="position-relative mx-2 my-2">
                                        <div class="position-absolute" style="right: 0px; top: 0px;">
                                            <form action="{{route('media.destroy', [ $media])}}" method="POST" id="delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" form="delete" class="btn btn-sm btn-danger mt-2 mr-2"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                        <img src="{{$media->url}}" class="img-thumbnail" height="150" width="150">
                                    </div>
                                @endforeach
                                    <!--Add new photo button-->
                                    <div class="align-self-center mx-2 my-2">
                                        <button class="btn btn-lg btn-secondary rounded-circle"><i
                                                class="fa fa-plus"></i></button>
                                    </div>
                                    <!--/Add new photo button-->
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
