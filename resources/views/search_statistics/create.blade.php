@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 px-0 px-md-4">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex">
                        <div class="align-self-center flex-grow-1">Create new search phrase</div>

                        <div class="align-self-center text-right">
                            <a href="{{ route('search-statistics.index') }}" class="btn btn-danger"> Cancel</a>
                            <button form="create-search-statistics-form" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>

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
                        <form method="POST" action="{{ route('search-statistics.store') }}" id="create-search-statistics-form">
                            @csrf

                            <div class="form-group">
                                <label for="source">Source</label>

                                <input type="text" name="source" class="form-control" id="source" placeholder="Source" value="{{old('source')}}"/>
                            </div>

                            <div class="form-group">
                                <label for="phrase">Phrase</label>

                                <input type="text" name="phrase" class="form-control" id="phrase" placeholder="Phrase" value="{{old('phrase')}}"/>
                            </div>

                            <div class="form-group">
                                <label for="last-upd-date">Last update date</label>

                                <input type="text" name="last-upd-date" class="form-control" id="last-upd-date" placeholder="yyyy-mm-dd h:m:s" value="{{old('last-upd-date')}}" />
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
