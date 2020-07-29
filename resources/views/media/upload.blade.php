@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Upload media | #{{$product->id}} {{$product->name}}
                            </div>
                            <div class="align-self-center text-right">
                                <a href="{{\Illuminate\Support\Facades\URL::previous()}}"
                                   class="btn btn-danger">Cancel</a>
                                <button type="submit" form="update" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST" action="{{route('media.update', [$product])}}" id="update" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                @for($i = 1; $i <= 10; $i++)
                                    <label for="file_{{$i}}">Select file #{{$i}} for upload</label>
                                    <input type="file" class="form-control-file" id="file_{{$i}}" name="file_{{$i}}">
                                @endfor
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
