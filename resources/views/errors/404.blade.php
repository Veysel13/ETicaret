@extends("layouts.master")
@section("title","404")
@section("content")
   <div class="container">
       <div class="jumbotron text-center">
           <h1>404</h1>
           <h1>Page Not Found</h1>
           <a href="{{route('home')}}" class="btn btn-primary">Home Page Return</a>
       </div>
   </div>
@endsection
