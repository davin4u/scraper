@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">Total:</div>
                            <button class="btn btn-info" data-toggle="modal" data-target="#my-modal">Show modal window
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.notifications.success')
                        @include('partials.notifications.errors')
                        <div>
                            @forelse($products as $product)
                                <div class="card mb-2">
                                    <div class="card-header">
                                        <div class="d-flex">
                                            <div class="align-self-center flex-grow-1">
                                                ID: {{$product->id}}
                                            </div>
                                            <div class="align-self-center text-right" style="width: 100px;">
                                                <a href="" class="btn btn-sm btn-primary"><i
                                                        class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{$product->store->domain->name}}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-secondary" role="alert">
                                    No products.
                                </div>
                            @endforelse
                        </div>
                        @if(!empty($products))
                            {{$products->render()}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div id="my-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Matching</h5>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('matching.search')}}" id="filters">
                            <table class="table">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">ID</th>
                                    <th scope="col" style="width: 150px;">Name</th>
                                    <th scope="col">Domain name</th>
                                    <th scope="col" style="min-width:90px;"></th>
                                </tr>
                                <tr>
                                    <th scope="col">
                                        <input value="{{request('id', old('id'))}}" type="text" name="id" id="id"
                                               form="filters" class="form-control"/>
                                    </th>
                                    <th scope="col">
                                        <input value="{{request('name', old('name'))}}" type="text" name="name"
                                               id="name" form="filters" class="form-control"/>
                                    </th>
                                    <th scope="col">
                                        <select class="form-control" name="domain" id="domain" form="filters">
                                            <option></option>
                                            @foreach($domains as $domain)
                                                <option value="{{$domain->id}}">{{$domain->name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th scope="col">
                                        <button class="btn btn-primary float-right" type="submit" form="filters"><i
                                                class="fa fa-search"></i></button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="output">

                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.onload = function () {

            document.getElementById('filters').addEventListener('submit', e => {
                e.preventDefault();

                let id = document.getElementById('id').value;
                let name = document.getElementById('name').value;
                let domain = document.getElementById('domain').value;
                let url = 'http://scraper.test/matching-tool/search';
                let csrf = document.querySelector('meta[name="csrf-token"]').content;
                let output = document.querySelector('.output');

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-type': 'application/json',
                        'x-csrf-token': csrf
                    },
                    credentials: "same-origin",
                    body: JSON.stringify({id: id, name: name, domain: domain})
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        output.innerHTML = '';

                        for (let key in data) {
                            output.innerHTML += `
                            <tr>
                                <td>${data[key].id}</td>
                                <td>${data[key].name}</td>
                                <td>domain</td>
                                <td class="text-right">
                                    <a href="" class="inline btn btn-sm btn-primary">Match</a>
                                </td>
                            </tr>`;
                        }
                    })
                    .catch(error => console.error(error));
            });

        };
    </script>
@endsection
