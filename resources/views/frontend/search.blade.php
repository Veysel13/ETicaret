@extends("layouts.master")
@section("title","Arama")
@section("content")
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="{{route("home")}}">Home</a></li>
            <li class="active">Result</li>
        </ol>

        <div class="products bg-content">
            <div class="row">

                   <div class="col-md-12">

                       <ul class="nav nav-tabs" role="tablist">
                           <li role="presentation" class="active"><a href="#product" data-toggle="tab">Product</a></li>
                           <li role="presentation"><a href="#restaurant" data-toggle="tab">Restaurant</a></li>
                       </ul>

                       <div class="tab-content">
                           <div role="tabpanel" class="tab-pane active" id="product">
                               <div class="row">
                                   @if(isset($products) && count($products)>0)
                                       @foreach($products as $product)
                                           <div class="col-md-3 product">
                                               <a href="{{route("product",$product['slug'])}}">
                                                   <img src="{{$product['imageUrl']}}">
                                               </a>
                                               <p><a href="{{route("product",$product['slug'])}}">{{$product['name']}}</a></p>
                                               <p class="price">{{$product['price']}} ₺</p>
                                           </div>
                                       @endforeach
                                   @else
                                       <div class="col-md-12">
                                           <div class="alert alert-primary" role="alert">
                                               Product Not Found
                                           </div>
                                       </div>
                                   @endif

                                   {{--                {{$products->appends(["search"=>old("search")])->links()}}--}}

                               </div>
                           </div>
                           <div role="tabpanel" class="tab-pane" id="restaurant">
                               <div class="row">
                                   @if(isset($restaurants) && count($restaurants)>0)
                                       @foreach($restaurants as $restaurant)
                                           <div class="col-md-3 product">
                                               <a href="{{route("restaurant",$restaurant['id'])}}">
                                                   <img src="{{$restaurant['logoUrl']}}">
                                               </a>
                                               <p><a href="{{route("restaurant",$restaurant['id'])}}">{{$restaurant['name']}}</a></p>
                                           </div>
                                       @endforeach
                                   @else
                                       <div class="col-md-12">
                                           <div class="alert alert-primary" role="alert">
                                               Restaurant Not Found
                                           </div>
                                       </div>
                                   @endif
                               </div>
                           </div>
                       </div>
                   </div>

            </div>
        </div>
    </div>


    </script>

@endsection

@section('footer')
    <script>
        const triggerTabList = document.querySelectorAll('#myTab button')
        triggerTabList.forEach(triggerEl => {
            const tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', event => {
                event.preventDefault()
                tabTrigger.show()
            })
        })
        </script>
@endsection
