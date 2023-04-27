<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware("guest")->except("oturumukapat");
    }

    public function loginForm(){

        return view("frontend.user.login");
    }

    public function registerForm(){
        return view("frontend.user.register");
    }

    public function login(Request $request){

        $this->validate($request,array(
            "email"=>"required|email",
            "password"=>"required|min:5|max:15",
        ));

        //user model içinde  getAuthPassword() fonksiyonu overrride edilir
        if (auth()->attempt(['email'=>$request["email"],"password"=>$request["password"]],$request->has("benihatirla"))){
            $request->session()->regenerate();

            $aktif_sepet_id=Cart::aktif_sepet();
            if (is_null($aktif_sepet_id)){
                $aktif_sepet=Cart::crreate(["kullanici_id"=>auth()->id()]);
                $aktif_sepet_id=$aktif_sepet->id;
            }
            session()->put("aktif_sepet_id",$aktif_sepet_id);

            return redirect()->intended('/home');
        }else{
            $errors=["email"=>"Hatalı Giriş"];
            return back()->withErrors($errors);
        }
    }

    public function register(Request $request){

        $this->validate($request,array(
            "fullname"=>"required|min:5|max:60",
            "email"=>"required|email|unique:users",
            "password"=>"required|confirmed|min:5|max:15",
        ));

        $kullanici=User::create(array(
            "fullname"=>$request["fullname"],
            "password"=>Hash::make($request["sifre"]),
            "email"=>$request["email"],
            "activation_key"=>Str::random(60),
            "status"=>0
        ));

        $kullanici->detay()->save(new UserDetail());

        auth()->login($kullanici);

        return redirect()->route("home");
    }

    public function logout(Request $request){

        auth()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route("home");
    }
}
