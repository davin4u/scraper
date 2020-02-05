@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit domain</div>

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
                            <form method="POST" action="{{ route('domains.update', [$domain]) }}">
                                <input type="hidden" name="_method" value="PUT" />
                                @csrf

                                <div class="form-group">
                                    <label for="name">Domain Name</label>

                                    <input type="text" name="name" class="form-control" id="name" placeholder="Domain Name" value="{{ $domain->name }}" />
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
