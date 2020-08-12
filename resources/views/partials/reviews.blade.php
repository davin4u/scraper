@forelse($productReviews as $review)
    <div class="card mb-2">
        <div class="card-header">
            <div class="d-flex">
                <div class="align-self-center flex-grow-1">
                    [{{$review->rating}}] {{$review->title}}
                </div>
                <div class="align-self-center text-right" style="width: 100px;">
                    <a href="{{route('products.reviews.edit', [$review])}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                    <form method="POST" action="{{route('products.reviews.destroy', [$review])}}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="inline btn btn-sm btn-danger" type="submit" ><i class="fa fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <p class="card-text">{{ $review->shortBody }}</p>
        </div>
    </div>
@empty
    <div class="alert alert-secondary" role="alert">
        No reviews added yet.
    </div>
@endforelse
