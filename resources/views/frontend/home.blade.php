@extends("layouts.master")
@section("title","Home Page")
@section("content")
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Categories</div>
                    <div class="list-group categories">
                        @foreach($categories as $category)
                            <a href="{{route('category',$category->slug)}}" class="list-group-item">
                                <i class="fa fa-arrow-circle-o-right"></i>
                                {{$category->name}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @for($i=0;$i<count($product_sliders);$i++)
                            <li data-target="#carousel-example-generic" data-slide-to="{{$i}}"
                                class="{{$i==0?"active":""}}"></li>
                        @endfor
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        @foreach($product_sliders as $index=>$product_slider)
                            <div class="item {{$index==0?"active":""}}">
{{--                                <img src="http://via.placeholder.com/640x400?text=UrunResmi" alt="...">--}}
                                <img src="{{$product_slider->product->image_url}}" alt="...">
                                <div class="carousel-caption">
                                    {{$product_slider->product->name}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            @if($product_opportunity)
                <div class="col-md-3">
                    <div class="panel panel-default" id="sidebar-product">
                        <div class="panel-heading">Deal of the Day</div>
                        <div class="panel-body">
                            <a href="{{route("product",$product_opportunity->slug)}}">
                                <img src="{{$product_opportunity->image_url}}" class="img-responsive">
                                {{$product_opportunity->name}}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <div class="container">
        <div class="products">
            <div class="panel panel-theme">
                <div class="panel-heading">Featured Products</div>
                <div class="panel-body">
                    <div class="row">
                        @foreach($opportunities as $opportunity)
                            <div class="col-md-3 product">
                                <a href="{{route("product",$opportunity->slug)}}">
{{--                                    <img src="http://via.placeholder.com/400x400?text=UrunResmi">--}}
                                    <img src="{{$opportunity->image_url}}">
                                </a>
                                <p><a href="#">{{$opportunity->name}}</a></p>
                                <p class="price">{{$opportunity->price}} ₺</p>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="products">
            <div class="panel panel-theme">
                <div class="panel-heading">Best Selling Products</div>
                <div class="panel-body">
                    <div class="row">
                        @foreach($best_sellers as $best_seller)
                            <div class="col-md-3 product">
                                <a href="{{route("product",$best_seller->slug)}}">
                                    <img src="{{$best_seller->image_url}}">
                                </a>
                                <p><a href="#">{{$best_seller->name}}</a></p>
                                <p class="price">{{$best_seller->price}} ₺</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="products">
            <div class="panel panel-theme">
                <div class="panel-heading">Discounted products</div>
                <div class="panel-body">
                    <div class="row">
                        @foreach($discounts as $discount)
                            <div class="col-md-3 product">
                                <a href="{{route("product",$discount->slug)}}">
                                    <img src="{{$discount->image_url}}">
                                </a>
                                <p><a href="#">{{$discount->name}}</a></p>
                                <p class="price">{{$discount->price}} ₺</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

