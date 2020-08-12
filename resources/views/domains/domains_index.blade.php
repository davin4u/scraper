@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Domains | Total: {{$domains->total()}}</div>
                            <div class="align-self-center text-right">
                                <a href="{{route('domains.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add domain</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')

                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 100px;">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Url</th>
                                <th scope="col" style="width: 120px;"></th>
                            </tr>
                            <tr>
                                <th scope="col">
                                    <input type="text" class="form-control" />
                                </th>
                                <th scope="col">
                                    <input type="text" class="form-control" />
                                </th>
                                <th scope="col">
                                    <input type="text" class="form-control" />
                                </th>
                                <th scope="col" ></th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($domains as $domain)
                            <tr>
                                <td>{{$domain->id}}</td>
                                <td>{{$domain->name}}</td>
                                <td>{{$domain->url}}</td>
                                <td class="text-right">
                                    <a href="{{route('domains.edit', [$domain])}}" class="inline btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <form method="POST" action="{{route('domains.destroy', [$domain])}}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline btn btn-sm btn-danger" type="submit" ><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-secondary" role="alert">
                                    No domains found.
                                </div>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
