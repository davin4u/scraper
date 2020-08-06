@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Edit author | {{$reviewAuthor->name}}</div>
                            <div class="align-self-center text-right">
                                <a href="{{route('authors.index')}}" class="btn btn-danger ">Cancel</a>
                                <button form="store" type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST" action="{{route('authors.update', [$reviewAuthor])}}" id="store">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Имя</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="name" name="name" value="{{old('name', $reviewAuthor->name)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="platform" class="col-sm-3 col-form-label">Платформа</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="platform" name="platform" value="{{old('platform', $reviewAuthor->platform)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rating" class="col-sm-3 col-form-label">Рейтинг</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="rating" name="rating" value="{{old('rating', $reviewAuthor->rating)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total_reviews" class="col-sm-3 col-form-label">Всего обзоров</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="total_reviews" name="total_reviews" value="{{old('total_reviews', $reviewAuthor->total_reviews)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="profile_url" class="col-sm-3 col-form-label">Ссылка на профиль</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="profile_url" name="profile_url" value="{{old('profile_url', $reviewAuthor->profile_url)}}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
