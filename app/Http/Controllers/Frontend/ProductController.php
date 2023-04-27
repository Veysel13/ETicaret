<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Libraries\ElasticSearch\ElasticSearch;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index($slug)
    {

        $product = Product::whereslug($slug)->firstorFail();
        $blade["product"] = $product;
        $blade['categories'] = $product->categories()->distinct()->get();

        return view("frontend.product", $blade);
    }

    public function search(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 0);
        $type = $request->input('type', '');

        $blade = [];

        if ($type == 'product')
            $searchValues = ElasticSearch::instance()->productSearch($search, $page);
        else
            $searchValues = ElasticSearch::instance()->restaurantSearch($search, $page);

        if (isset($searchValues['items']) && count($searchValues['items']) > 0) {

            $datas = [];
            foreach ($searchValues['items'] as $index => $searchValue) {

                $searchValue['logoUrl'] = imageUrl($searchValue['logo'] ?? '');
                $searchValue['imageUrl'] = imageUrl($searchValue['image'] ?? '');

                array_push($datas, $searchValue);
            }

            $blade['datas'] = $datas;
            $total = $searchValues['total']['value'] ?? 0;
            $blade['total'] = $total;
            $size = $searchValues['size'] ?? 0;
            $blade['size'] = $size;
            $blade['totalPage'] =  intval(ceil($total/$size));

        } else {
            $blade['products'] = Product::where("name", "like", "%$search%")
                ->orWhere("description", "like", "%$search%")
                ->paginate(8);
        }

        $request->flash();

        return view("frontend.search", $blade);
    }
}
