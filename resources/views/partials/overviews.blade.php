@forelse($productOverviews as $overview)
    <div class="card mb-2">
        <div class="card-header">
            <div class="d-flex">
                <div class="align-self-center flex-grow-1">
                    {{$overview->name}}
                </div>
                <div class="align-self-center text-right" style="width: 100px;">
                    <a href="{{route('products.overviews.edit', [$overview])}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                    <form method="POST" action="{{route('products.overviews.destroy', [$overview])}}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="inline btn btn-sm btn-danger" type="submit" ><i class="fa fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <p class="card-text">{{$overview->url}}</p>
        </div>
    </div>
@empty
    <div class="alert alert-secondary" role="alert">
        No overviews added yet.
    </div>
@endforelse
