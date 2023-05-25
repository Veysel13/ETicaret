<?php

namespace App\Repositories\Basket;

use App\Models\Restaurant;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Restaurant\RestaurantInterface;

class BasketRepository implements BasketInterface
{
    private $product;
    private $restaurant;

    public function __construct(?ProductInterface $product, ?RestaurantInterface $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->product = $product;
    }

    public function add(int $productId, float $quantity, string $description = '', bool $update = false): array
    {
        $errors = [];
        $warnings = [];

        $product = $this->product->findById($productId);
        if (!$product) {
            $errors[] = 'Product not found';
        } elseif ($product->status !== 1) {
            $errors[] = $product->name . ' Product not found.';
        }

        $getRestaurant = \Cart::getRestaurant();
        if (count($errors) < 1 && $getRestaurant && \Cart::getTotalQuantity() > 0) {
            if ($product->restaurant_id !== $getRestaurant->id) {
                if ($getRestaurant->is_market == 1) {
                    $warnings[] = 'Sepetinizde şuan da başka markete ait ürünler bulunuyor, Bu marketten ürün eklemek için lütfen ilk sepetinizi boşaltınız.';
                } else {
                    $warnings[] = 'Sepetinizde şuan da başka restorana ait ürünler bulunuyor, Bu restorandan ürün eklemek için lütfen ilk sepetinizi boşaltınız.';
                }
            }
        }


        $cartId = $productId . ($description ? '_' . md5($description) : '');

        if (\Cart::has($cartId)) {
            if ($update) {
                $quantity = \Cart::get($cartId)->quantity + $quantity;
            }
        }

        $totalPrice = 0;

        if (count($errors) < 1 && count($warnings) < 1) {

            $data = [];
            $data['id'] = $cartId;
            $data['name'] = $product->name;
            $data['quantity'] = $quantity;
            $data['price'] = $product->price;
            $data['associatedModel'] = $product;
            $data['attributes'] = [
                'description' => $description,
                'productId' => $productId
            ];

            if (\Cart::getTotalQuantity() < 1) {
                $getRestaurant = $this->restaurant->findById($product->restaurant_id);
                \Cart::addRestaurant($getRestaurant);
            }

            \Cart::remove($cartId);
            \Cart::add($data);

            if (\Cart::has($cartId)) {
                $totalPrice = \Cart::get($cartId)->getPriceSum();
            }

            return [
                'status' => true,
                'message' => 'Ürün Sepete Eklendi',
                'quantity' => $quantity,
                'totalPrice' => $totalPrice
            ];
        } elseif ($warnings) {
            return [
                'status' => false,
                'message' => array_shift($warnings),
                'quantity' => $quantity,
                'totalPrice' => $totalPrice
            ];
        } else {
            return [
                'status' => 2,
                'message' => array_shift($errors),
                'quantity' => $quantity,
                'totalPrice' => $totalPrice
            ];
        }
    }

    public function all()
    {
        $content = \Cart::getContent();
        return $content->sortBy('name');
    }

    public function remove($foodId)
    {
        return \Cart::remove($foodId);
    }

    public function clear()
    {
        return \Cart::clear();
    }

    public function cartTotal()
    {
        return \Cart::getTotal();
    }

    public function subTotal()
    {
        return \Cart::getSubTotal();
    }

    public function totals()
    {
        $items = [];

        $item = [];
        $item['name'] = 'Sub Total';
        $item['key'] = 'sub_total';
        $item['price'] = \Cart::getSubTotal();
        $item['priceFormat'] = \Cart::getSubTotal();
        array_push($items, $item);

        foreach (\Cart::getConditions() as $condition) {

            $item = [];
            $item['name'] = $condition->getName();
            $item['key'] = $condition->getType();
            $item['price'] = $condition->getValue();
            $item['priceFormat'] = \Cart::getSubTotal();
            array_push($items, $item);
        }

        $item = [];
        $item['name'] = 'Total';
        $item['key'] = 'total';
        $item['price'] = \Cart::getTotal();
        $item['priceFormat'] = \Cart::getTotal();
        array_push($items, $item);
        return json_decode(json_encode($items), false);
    }

    public function addRestaurant(Restaurant $restaurant): bool
    {
        return \Cart::addRestaurant($restaurant);
    }

    public function getRestaurant()
    {
        return \Cart::getRestaurant();
    }

    public function removeRestaurant(): bool
    {
        return \Cart::removeRestaurant();
    }
}
