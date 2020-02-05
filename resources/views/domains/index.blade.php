@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header clearfix">
                        <span>Domains</span>
                        <a class="btn btn-primary float-right" href="{{ route('domains.create') }}">Create</a></div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th></th>
                            </thead>

                            <tbody>
                            @foreach($domains as $domain)
                                <tr>
                                    <td>{{ $domain->id }}</td>
                                    <td>{{ $domain->name }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('domains.edit', [$domain]) }}" class="btn btn-primary btn-sm">Edit</a>
                                        @if (auth()->user()->isAdmin())
                                            <form method="POST" action="{{ route('domains.destroy', [$domain]) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE" />

                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @empty($domains)
                                <tr><td colspan="3">No domains added</td></tr>
                            @endempty
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
