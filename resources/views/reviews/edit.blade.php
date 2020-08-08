@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Edit review #{{$productReview->id}} </div>
                            <div class="text-right align-self-center">
                                <a href="{{route('products.reviews.index')}}" class="btn btn-danger ">Cancel</a>
                                <button form="store" type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <form method="POST" action="{{route('products.reviews.update', [$productReview])}}" id="store">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="author" class="col-sm-3 col-form-label">Автор</label>
                                <div class="col-sm-7">
                                    <a href="{{route('authors.edit', [$reviewAuthor])}}" class="form-control btn btn-outline-secondary">{{$reviewAuthor->name}}</a>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-sm-3 col-form-label">Заголовок</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="title" name="title" value="{{old('title', $productReview->title)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="url" class="col-sm-3 col-form-label">Ссылка на отзыв</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="url" name="url" value="{{old('url', $productReview->url)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pros" class="col-sm-3 col-form-label">Достоинства</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="pros" name="pros" value="{{old('pros', $productReview->pros)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cons" class="col-sm-3 col-form-label">Недостатки</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="cons" name="cons" value="{{old('cons', $productReview->cons)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="body" class="col-sm-3 col-form-label">Текст отзыва</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" id="body" name="body" form="store">{{old('body', $productReview->body)}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="summary" class="col-sm-3 col-form-label">Общее впечатление</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="summary" name="summary" value="{{old('summary', $productReview->summary)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bought_at" class="col-sm-3 col-form-label">Год покупки</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="bought_at" name="bought_at" value="{{old('bought_at', $productReview->bought_at)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="product_id" class="col-sm-3 col-form-label">Привязать по ID</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="product_id" name="product_id" value="{{old('product_id', $productReview->product_id)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rating" class="col-sm-3 col-form-label">Рейтинг</label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="rating" name="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                        <option value="{{$i}}" @if($productReview->rating == $i) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" @if($productReview->i_recommend) checked @endif class="form-check-input" id="i_recommend" name="i_recommend">
                                <label class="form-check-label" for="i_recommend">Рекоммендую</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
