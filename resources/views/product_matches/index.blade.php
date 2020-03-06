@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header clearfix">
                        <span>Product matches</span>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <th>ID</th>
                                <th>Product</th>
                                <th>ID</th>
                                <th>Match</th>
                                <th></th>
                            </thead>

                            <tbody>
                            @foreach($matches as $match)
                                <tr>
                                    <td>{{ $match['product']['id'] }}</td>

                                    <td>
                                        <div>{{ $match['product']['name'] }}</div>
                                        <div><small>{{ $match['product']['domain']['name'] }}, {{ $match['product']['category']['name'] }}</small></div>
                                    </td>

                                    <td>{{ $match['match']['id'] }}</td>

                                    <td>
                                        <div>{{ $match['match']['name'] }}</div>
                                        <div><small>{{ $match['match']['domain']['name'] }}, {{ $match['match']['category']['name'] }}</small></div>
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('products.resolve', [$match['id']]) }}" class="btn btn-primary btn-sm">Merge</a>
                                    </td>
                                </tr>
                            @endforeach
                            @empty($matches)
                                <tr><td colspan="3">No matches found</td></tr>
                            @endempty
                            </tbody>
                        </table>

                        {{ $paginator->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection