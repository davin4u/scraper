@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{$error}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endforeach
@endif