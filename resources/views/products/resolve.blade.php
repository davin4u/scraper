@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Merge user products</div>

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

                <form action="{{ route('products.merge', [$match['id']]) }}" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card border-light mb-3">
                                <div class="card-header">
                                    <h4>{{ $match['product']['name'] }}</h4>
                                    <small>{{ $match['product']['domain']['name'] }}</small>
                                </div>

                                <div class="card-body">
                                    @foreach($match['product']['attributes'] as $attribute)
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{ $attribute['name'] }}</label>

                                            <div class="col-sm-8">
                                                <input type="text" name="attributes[{{ $attribute['attribute_key'] }}]" class="form-control" placeholder="{{ $attribute['name'] }}" value="{{ $attribute['value'] ?: '' }}" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-light mb-3">
                                <div class="card-header">
                                    <h4>{{ $match['match']['name'] }}</h4>
                                    <small>{{ $match['match']['domain']['name'] }}</small>
                                </div>

                                <div class="card-body">
                                    @foreach($match['match']['attributes'] as $attribute)
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{ $attribute['name'] }}</label>

                                            <div class="col-sm-6">
                                                <span>{{ $attribute['value'] ?: '-' }}</span>
                                            </div>

                                            <div class="col-sm-2 text-right">
                                                <button class="btn btn-primary btn-sm" onclick="useIt('{{ $attribute['attribute_key'] }}', '{{ $attribute['value'] }}')">Use it</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Merge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function useIt(attr, value) {
            let el = document.querySelector('[name="attributes[' + attr + ']"]');

            if (el) {
                el.value = value;
            }

            return false;
        }
    </script>
@endsection