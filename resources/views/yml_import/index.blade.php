@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 px-0 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="align-self-center flex-grow-1">
                                <form method="POST" action="{{route('yml-import.upload')}}" id="upload" enctype="multipart/form-data">
                                    @csrf
                                    <div>
                                        <label for="yml-file">Select yml file for upload: </label>
                                        <input type="file" id="yml-file" name="yml-file"/>
                                    </div>
                                </form>
                            </div>
                            <div class="align-self-center flex-grow-1">
                                <button class="btn btn-primary float-right" form="upload" type="submit">Upload file</button>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partials.notifications.success')
                @include('partials.notifications.errors')
                @if(!empty(session('duplicatedOffers')))
                    @php
                        $duplicatedOffers = session('duplicatedOffers');
                    @endphp
                    <div class="card-body">
                        <div>Duplicates: {{count($duplicatedOffers)}}</div>
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 100px;">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Category</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($duplicatedOffers as $duplicatedOffer)
                                <tr>
                                    <td>{{$duplicatedOffer['id']}}</td>
                                    <td>{{$duplicatedOffer['name']}}</td>
                                    <td>{{$duplicatedOffer['category']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
