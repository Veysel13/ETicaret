<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Libraries\ElasticSearch\ElasticSearch;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index($id)
    {
        $restaurant = Restaurant::where('id',$id)->where('status',1)->first();
        $blade["restaurant"] = $restaurant;
        $blade['products'] = Product::where('restaurant_id',$id)->where('status',1)->paginate(8);

        return view("frontend.restaurant.index", $blade);
    }
}
