<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(){

        if (!auth()->check()){

            return redirect()->route("user.login")
                ->with("mesaj_tur","info")
                ->with("mesaj","Şirariş verebilmek için lütfen giriş yapınız");
        }

        $sepet=Cart::where("user_id",auth()->id())->get();

        if ($sepet->count()==0){

            return redirect()->route("home")
                ->with("mesaj_tur","info")
                ->with("mesaj","Lütfen sepete ütün ekleyiniz.");
        }

        $blade['userDetail']=auth()->user()->detay;

        return view("frontend.payment",$blade);
    }

    public function payment(Request $request){

        $cartProductTotal=CartProduct::select(\DB::raw('sum(quantity*price) as sum'))->where('cart_id',session("active_cart_id"))->pluck('sum')->first();

        $order=array(
            "cart_id"=>session("active_cart_id"),
            "fullname"=>$request["fullname"],
            "address"=>$request["address"],
            "phone"=>$request["phone"],
            "phone2"=>$request["phone_2"],
            "bank"=>"Deneme",
            "installment_count"=>3,
            "description"=>"Şipariş alındı",
            "total_price"=>$cartProductTotal,
        );

        Order::create($order);
        session()->forget("active_cart_id");

        return redirect()->route("orders")
            ->with("mesaj_tur","info")
            ->with("mesaj","Şiparişiniz oluşturuldu.");
    }
}
