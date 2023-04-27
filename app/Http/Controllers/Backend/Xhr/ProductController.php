<?php

namespace App\Http\Controllers\Backend\Xhr;

use App\Http\Controllers\Controller;
use App\Jobs\ESRestaurantIndex;
use App\Models\Product;
use App\Models\Restaurant;
use App\Repositories\Product\ProductInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;
    public function __construct(ProductInterface $product)
    {
        $this->product=$product;
    }
    public function products(Request $request){

        $result = [];

        $stores =  Product::filter(request())
            ->where('status',1)
            ->orderBy('created_at')
            ->take(100)->get();

        $items = [];
        foreach ($stores as $store) {
            array_push($items, [
                'id' => $store->id,
                'text' => $store->name
            ]);
        }

        $result['restaurants'] = $items;
        return response()->json($result);
    }

    public function statusUpdate(Request $request)
    {
        $refId = $request->input('refId');
        $newId = $request->input('newId');

        $product = $this->product->findById($refId);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }

        $this->product->update($refId,['status' => $newId]);

        dispatch(new ESRestaurantIndex($product->restaurant_id));

        return response()->json([
            'status' => true,
            'message' => 'Product Status Update'
        ]);
    }

    public function priceUpdate(Request $request)
    {
        $refId = $request->input('refId');
        $newId = $request->input('newId');

        $productData = $this->product->findById($refId);
        if (!$productData) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }

        $this->product->update($refId,['price' => $newId]);

        return response()->json([
            'status' => true,
            'message' => 'Product Price Update'
        ]);
    }

    public function search(Request $request, int $restaurantId)
    {
        $term = $request->input('term') ?: '';

        $products = $this->product->findByRestaurantIdFoodsSearch($restaurantId, $term);

        $items = [];
        foreach ($products as $product) {
            $item = [];
            $item['id'] = $product->id;
            $item['text'] = $product->name;
            array_push($items, $item);
        }

        return response()->json([
            'status' => true,
            'results' => $items
        ]);
    }
}
