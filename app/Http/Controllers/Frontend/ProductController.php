<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Libraries\ElasticSearch\ElasticSearch;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index($slug_urunadi)
    {

        $product = Product::whereslug($slug_urunadi)->firstorFail();
        $blade["product"] = $product;
        $blade['categories'] = $product->categories()->distinct()->get();
        return view("frontend.product", $blade);
    }

    public function search(Request $request)
    {

        $search = $request->input('search', '');

        $blade = [];

        $searchValues = ElasticSearch::instance()->restaurantSearch($search, 0);

        if (isset($searchValues['items']) && count($searchValues['items']) > 0) {

            $restaurants = [];
            $products = [];

            foreach ($searchValues['items'] as $searchValue) {

                $restaurantItem = $searchValue;
                unset($restaurantItem['products']);
                $restaurantItem['logoUrl'] = imageUrl($searchValue['logo']);

                array_push($restaurants, $restaurantItem);

                foreach ($searchValue['products'] as $product) {

                    $productItem = $product;
                    $productItem['imageUrl'] = imageUrl($product['image']);

                    array_push($products, $productItem);
                }
            }

            $blade['restaurants'] = $restaurants;
            $blade['products'] = $products;
        } else {
            $blade['products'] = Product::where("name", "like", "%$search%")
                ->orWhere("description", "like", "%$search%")
                ->paginate(8);
        }

        $request->flash();

        return view("frontend.search", $blade);
    }
}
