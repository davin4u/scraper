 @extends('layouts.app')

 @section('content')
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-md-12 px-0 px-md-4">
                 <div class="card">
                     <div class="card-header">
                         <div class="d-flex">
                             <div class="align-self-center flex-grow-1">Edit user | #{{$user->id}} {{$user->name}}</div>
                             <div class="align-self-center text-right">
                                 <a href="{{route('users.index')}}" class="btn btn-danger">Cancel</a>
                                 <button form="edit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                             </div>
                         </div>
                     </div>
                     <div class="card-body">
                         @include('partials.notifications.success')
                         @include('partials.notifications.errors')
                         <form method="POST" action="{{route('users.update', [$user])}}" id="edit">
                             @csrf
                             @method('PUT')
                             <div class="row">
                                 <div class="col-md-8">

                                     <div class="form-group row">
                                         <label for="name" class="col-sm-3 col-form-label">Name</label>
                                         <div class="col-sm-7">
                                             <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}">
                                         </div>
                                     </div>

                                     <div class="form-group row">
                                         <label for="password" class="col-sm-3 col-form-label">password</label>
                                         <div class="col-sm-7">
                                             <input type="password" class="form-control" name="password" id="password">
                                         </div>
                                     </div>

                                 </div>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 @endsection
