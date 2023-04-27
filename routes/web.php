<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get("/","Frontend\HomeController@index");
Route::get("/home","Frontend\HomeController@index")->name("home");
Route::get("/category/{slug}","Frontend\CategoryController@index")->name("category");
Route::get("/product/{slug}","Frontend\ProductController@index")->name("product");
Route::get("/restaurant/{id}","Frontend\RestaurantController@index")->name("restaurant");

Route::group(["prefix"=>"cart"],function (){
    Route::get("/","Frontend\CartController@index")->name("cart");
    Route::post("/add","Frontend\CartController@add")->name("cart.add");
    Route::delete("/remove/{id}","Frontend\CartController@remove")->name("cart.remove");
    Route::patch("/update/{id}","Frontend\CartController@update")->name("cart.update");
    Route::delete("/clear","Frontend\CartController@clear")->name("cart.clear");
});

Route::get("/payment","Frontend\PaymentController@index")->name("payment");
Route::post("/payment","Frontend\PaymentController@payment")->name("payment");

Route::group(["middleware"=>"auth"],function (){
    Route::get("/orders","Frontend\OrderController@index")->name("orders");
    Route::get("/orders/{id}","Frontend\OrderController@detail")->name("orders.detail");
});


Route::post("/search","Frontend\ProductController@search")->name("search.product");
Route::get("/search","Frontend\ProductController@search")->name("search.product");


Route::prefix('user')->group(function () {
    Route::get("/login","Frontend\UserController@loginForm")->name("user.login");
    Route::post("/login","Frontend\UserController@login");
    Route::get("/register","Frontend\UserController@registerForm")->name("user.register");
    Route::post("/register","Frontend\UserController@register");
    Route::post("/logout","Frontend\UserController@logout")->name("user.logout");
});



