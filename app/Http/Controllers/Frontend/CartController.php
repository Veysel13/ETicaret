<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(){

        if (session("active_cart_id")){
            $cart=Cart::where("id",session("active_cart_id"))->first();
        }else{
            $cart=Cart::create([
                "user_id"=>auth()->id(),
            ]);

            session()->put('active_cart_id',$cart->id);
        }

        $blade['cart']=$cart;

        return view("frontend.cart",$blade);
    }

    public function add(Request $request){

        $product=Product::find($request["id"]);

        if(auth()->check()){
            $active_cart_id=session("active_cart_id");

            if (!isset($active_cart_id)){

                $active_cart=Cart::create([
                    "user_id"=>auth()->id(),
                ]);

                $active_cart_id=$active_cart->id;

                session()->put('active_cart_id',$active_cart_id);
            }

            CartProduct::updateOrCreate(
                ["cart_id"=>$active_cart_id,"product_id"=>$product->id],
                ["quantity"=>1,"price"=>$product->price,'description'=>'Wait']
            );
        }

        return redirect()->route("cart")
            ->with("mesaj_tur","success")
            ->with("mesaj","Product Add");
    }

    public function remove($row_id){

        if(auth()->check()){
            $active_cart_id=session("active_cart_id");
            CartProduct::where("id",$row_id)->delete();
        }

        return redirect()->route("cart")
            ->with("mesaj_tur","success")
            ->with("mesaj","Product Remove");
    }

    public function update($id,Request $request){


        $validator=Validator::make($request->all(),[
            "quantity"=>"required|numeric|between:1,50"
        ]);

        if ($validator->fails()){

            session()->flash("mesaj_tur","danger");
            session()->flash("mesaj","Adet değeri bir ile beş arsında olmalıdır");
            return response()->json(["success",false]);
        }


        $active_cart_id=session("active_cart_id");
        if ($request["quantity"]==0){
            CartProduct::where("cart_id",$active_cart_id)->where("id",$id)->delete();
        }else{
            CartProduct::where("cart_id",$active_cart_id)->where("id",$id)->update([
                "quantity"=>$request["quantity"]
            ]);
        }

        session()->flash("mesaj_tur","success");
        session()->flash("mesaj","Değer güncellendi");
        return response()->json(["success",true]);

    }

    public function clear(){
        if (auth()->check()){
            $active_cart_id=session("active_cart_id");
            CartProduct::where("cart_id",$active_cart_id)->delete();
        }

        return redirect()->route("cart")
            ->with("mesaj_tur","success")
            ->with("mesaj","Sepet başarı ile sepet boşaltıldı");
    }

}
