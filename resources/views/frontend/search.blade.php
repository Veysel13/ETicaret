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
                           <li role="presentation" class="{{request()->type!='restaurant'?'active':''}}"><a href="{{route('search.product',['type'=>'product','search'=>request()->search])}}">Product</a></li>
                           <li role="presentation" class="{{request()->type=='restaurant'?'active':''}}"><a href="{{route('search.product',['type'=>'restaurant','search'=>request()->search])}}">Restaurant</a></li>
                       </ul>

                       <div class="tab-content">
                           <div role="tabpanel" class="tab-pane {{request()->type!='restaurant'?'active':''}}" id="product">
                               <div class="row">
                                   @if(isset($datas) && count($datas)>0 && request()->type!='restaurant')
                                       @foreach($datas as $product)
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
                           <div role="tabpanel" class="tab-pane {{request()->type=='restaurant'?'active':''}}" id="restaurant">
                               <div class="row">
                                   @if(isset($datas) && count($datas)>0 && request()->type=='restaurant')
                                       @foreach($datas as $restaurant)
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
