<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index($slug){

        $category=Category::where("slug",$slug)->firstOrFail();
        $blade['category']=$category;

        $blade['subCategories']=Category::where("parent_id",$category->id)->get();

        $order=request("order");
        if ($order=="bestsellers"){

            $blade['products']=$category->products()
                ->distinct()
                ->join("product_details","product_details.product_id","=","products.id")
                ->orderBy("product_details.is_bestseller","desc")
                ->paginate(4);
        }else if ($order=="new"){
            $blade['products']=$category->products()->distinct()->orderByDesc("updated_at")->paginate(4);
        }else{
            $blade['products']=$category->products()->distinct()->paginate(4);
        }

        return view("frontend.category",$blade);
    }
}
