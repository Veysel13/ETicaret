
@extends("layouts.master")
@section("title","Sepet")
@section("content")
    <div class="container">
        <div class="bg-content">
            <h2>Cart</h2>
            @include("layouts.partials.alert")
            @if(count($items)>0)
            <table class="table table-bordererd table-hover">
                <tr>
                    <th colspan="2">Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
                @foreach($items as $item)
                    <tr>
                        <td> <img src="http://via.placeholder.com/120x100?text=UrunResmi"> </td>
                        <td><a href="{{route("product",1)}}">
                                {{$item->name}}
                            </a>

                            <form action="{{route("cart.remove",$item->id)}}" method="post">
                                @method('DELETE')
                                @csrf
                                <input type="submit" class="btn btn-danger" value="Remove From Cart">
                            </form>
                        </td>
                        <td>{{$item->price}} ₺</td>

                        <td>
                            <a data-id="{{$item->id}}" data-adet="{{$item->quantity-1}}" class="btn btn-xs btn-default urun-adet-azalt">-</a>
                            <span style="padding: 10px 20px">{{$item->quantity}}</span>
                            <a data-id="{{$item->id}}" data-adet="{{$item->quantity+1}}" class="btn btn-xs btn-default urun-adet-artir">+</a>
                        </td>

                        <td>{{$item->price*$item->quantity}}</td>
                    </tr>
                @endforeach

                @foreach($totals as $total)
                    <tr>
                        <th colspan="4" class="text-right">{{$total->name}}</th>
                        <th>{{$total->price}} ₺</th>
                    </tr>
                @endforeach

            </table>
                <div>
                    <form action="{{route("cart.clear")}}" method="post">
                        @method('DELETE')
                        @csrf
                        <input type="submit" class="btn btn-danger" value="Empty Basket">
                    </form>
                    <a href="{{route("payment")}}" class="btn btn-success pull-right btn-lg">Payment</a>
                </div>
            @else
                <p>Cart Empty</p>
            @endif

        </div>
    </div>
@endsection

@section("footer")
    <script>
        $(function () {
            $(".urun-adet-artir,.urun-adet-azalt").on("click",function () {
                var id=$(this).attr('data-id');
                var quantity=$(this).attr('data-adet');
                $.ajax({
                    type:"PATCH",
                    url:'{{url('cart/update')}}/'+id,
                    data:{quantity:quantity,_token: '{{csrf_token()}}'},
                    success:function (result) {
                        window.location.href="/cart";
                    }
                });
            })
        })
    </script>
    @endsection
