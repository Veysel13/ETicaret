<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Basket\ItemResource;
use App\Http\Resources\Basket\TotalResource;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Repositories\Basket\BasketInterface;
use App\Repositories\Product\ProductInterface;
use Illuminate\Http\Request;
use Validator;

class CartController extends Controller
{
    public $basket;
    public $product;

    public function __construct(BasketInterface $basket, ProductInterface $product)
    {
        $this->middleware("auth");

        $this->basket = $basket;
        $this->product = $product;
    }

    public function index()
    {
        $items = [];
        $contents = $this->basket->all();

        foreach ($contents as $content) {

            $productId = $content->attributes['productId'];
            $content->categoryName = '';
            $categoryFood = CategoryProduct::where('product_id', $productId)->first();
            if ($categoryFood) {
                $category = Category::where('id', $categoryFood->category_id)->first();
                if ($category) {
                    $content->categoryName = $category->name;
                }
            }

            $product = $content->associatedModel;

            array_push($items, new ItemResource($content));
        }
        $blade['items'] = $items;

        $items = [];
        $totals = $this->basket->totals();
        foreach ($totals as $total) {
            array_push($items, new TotalResource($total));
        }
        $blade['totals'] = $items;
        $blade['count'] = $contents->count();

        $getRestaurant = $this->basket->getRestaurant();
        if ($getRestaurant) {
            $blade['restaurantName'] = $getRestaurant->name;
            $blade['restaurantId'] = $getRestaurant->id;
        }

        return view("frontend.cart", $blade);
    }

    public function add(Request $request)
    {
        $product = $this->product->findById($request->input('id'));
        if (!$product) {
            return redirect()->route("cart")
                ->with("mesaj_tur", "warning")
                ->with("mesaj", "Product Not Found");
        }

        $add = $this->basket->add($product->id, 1, '', 1);
        if ($add['status'] === true) {
            $result['status'] = true;
            $result['message'] = $add['message'];
            $result['count'] = $this->basket->all()->count();
            $result['quantity'] = $add['quantity'];
            $result['totalPriceFormat'] = priceFormat($add['totalPrice']);

            return redirect()->route("cart")
                ->with("mesaj_tur", "success")
                ->with("mesaj", "Product Add");
        } else {
            $result['status'] = $add['status'];
            $result['title'] = 'Seçim Yapılamaz';
            $result['message'] = $add['message'];
            $result['count'] = $this->basket->all()->count();
            $result['quantity'] = $add['quantity'];
            $result['totalPriceFormat'] = priceFormat($add['totalPrice']);

            return redirect()->route("cart")
                ->with("mesaj_tur", "warning")
                ->with("mesaj", "Product Add Warning");
        }
    }

    public function remove($row_id)
    {

        $this->basket->remove($row_id);

        return redirect()->route("cart")
            ->with("mesaj_tur", "success")
            ->with("mesaj", "Product Remove");
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "quantity" => "required|numeric|between:1,50"
        ]);

        if ($validator->fails()) {
            session()->flash("mesaj_tur", "danger");
            session()->flash("mesaj", "Adet değeri bir ile beş arsında olmalıdır");
            return response()->json(["success", false]);
        }

        if ($request->input('quantity') == 0) {
            $this->basket->remove($id);
        } else {
            $this->basket->add($id, $request->input('quantity'), '', 0);
        }

        session()->flash("mesaj_tur", "success");
        session()->flash("mesaj", "Değer güncellendi");
        return response()->json(["success", true]);

    }

    public function clear()
    {
        \Cart::clearCartConditions();
        $this->basket->clear();

        return redirect()->route("cart")
            ->with("mesaj_tur", "success")
            ->with("mesaj", "Sepet başarı ile sepet boşaltıldı");
    }

}
