@extends('layouts.app')

@section('content')
    <MatchingTool></MatchingTool>
@endsection

@section('scripts')
    <script>
        window.categories = {!! \App\Category::all() !!};
        window.brands = {!! \App\Brand::all() !!};
        window.domains = {!! \App\Domain::all() !!};
    </script>
@endsection
