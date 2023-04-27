<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;

class HomeController extends Controller
{

    public function index(){

        //restoran ekleme
        //dispatch(new ESRestaurantIndex(1));

        $blade['categories']=Category::whereRaw("parent_id is null")->take(8)->get();

        $blade['product_sliders']=ProductDetail::with("product")
            ->where("is_slider",1)
            ->take(5)
            ->get();

        $blade['product_opportunity']=Product::select("products.*")
            ->join("product_details","product_details.product_id","=","products.id")
            ->where("products.status",1)
            ->where("product_details.is_opportunity",1)
            ->orderBy("products.updated_at",'desc')
            ->first();

        $blade['opportunities']=Product::select("products.*")
            ->join("product_details","product_details.product_id","=","products.id")
            ->where("products.status",1)
            ->where("is_opportunity",1)
            ->take(4)
            ->get();

        $blade['best_sellers']=Product::select("products.*")
            ->join("product_details","product_details.product_id","=","products.id")
            ->where("products.status",1)
            ->where("is_bestseller",1)
            ->take(4)
            ->get();

        $blade['discounts']=Product::select("products.*")
            ->join("product_details","product_details.product_id","=","products.id")
            ->where("products.status",1)
            ->where("is_discount",1)
            ->take(4)
            ->get();

        return view("frontend.home",$blade);
    }
}
