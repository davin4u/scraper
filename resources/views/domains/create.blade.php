@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create new domain</div>

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
                            <form method="POST" action="{{ route('domains.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Domain name</label>

                                    <input type="text" name="name" class="form-control" id="name" placeholder="Domain name" />
                                </div>

                                <div class="form-group">
                                    <label for="url">Domain url</label>

                                    <input type="text" name="url" class="form-control" id="url" placeholder="Domain url" />
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('domains.index') }}" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
