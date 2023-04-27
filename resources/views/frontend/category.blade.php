@extends("layouts.master")
@section("title",$category->name)
@section("content")
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="{{route("home")}}">Home</a></li>
            <li class="active">{{$category->name}}</li>
        </ol>
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$category->name}}</div>
                    <div class="panel-body">
                        <h3>Sub Categories</h3>
                        <div class="list-group categories">
                            @foreach($subCategories as $subCategorie)
                            <a href="{{route('category',$subCategorie->slug)}}" class="list-group-item">
                                <i class="fa fa-television"></i>
                                {{$subCategorie->name}}
                            </a>
                                @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="products bg-content">
                    Sırala
                    <a href="?order=bestsellers" class="btn btn-default">Bestsellers</a>
                    <a href="?order=new" class="btn btn-default">New Products</a>
                    <hr>
                    <div class="row">
                        @if(count($products)==0)
                            <div class="col-md-12">
                                <div class="alert alert-primary" role="alert">
                                    Product Not Found
                                </div>
                            </div>
                            @endif
                        @foreach($products as $product)
                            <div class="col-md-3 product">
                                {{--http://via.placeholder.com/400x400?text=UrunResmi--}}
                                <a href="{{route("product",$product->slug)}}">
                                    <img src="{{$product->image_url}}">
                                </a>
                                <p><a href="{{route("product",$product->slug)}}">{{$product->name}}</a></p>
                                <p class="price">{{$product->price}} ₺</p>
                                <p><a href="#" class="btn btn-theme">Sepete Ekle</a></p>
                            </div>
                            @endforeach

                    </div>
                    {{ request()->has("order")? $products->appends(["order"=>request("order")])->links():$products->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
