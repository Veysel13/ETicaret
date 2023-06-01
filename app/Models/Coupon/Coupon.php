<?php

namespace App\Models\Coupon;

use App\Constants\OrderStatus;
use App\Models\Order\OrderCoupon;
use App\Repositories\Basket\BasketRepository;
use Carbon\Carbon;
use Darryldecode\Cart\CartCondition;

class Coupon
{
    private $code;
    private $userId;
    private $basketRepository;
    private $couponDB;
    private $name;
    private $conditionKey;
    private $message;

    public function __construct($code, $userId = 0, $email = null, $gsm = null)
    {
        $this->basketRepository = new BasketRepository(null, null);
        $this->code = $code;
        $this->userId = $userId;

        $getCoupon = $this->getCoupon();

        if ($getCoupon) {
            $this->couponDB = $getCoupon;
            $this->name = $getCoupon->name;
        }
    }

    public function isUses()
    {
        if (auth()->guest()) {
            return false;
        }

        $cartRestaurant = $this->basketRepository->getRestaurant();
        if (!$cartRestaurant) {
            return false;
        }

        if (!$this->couponDB) {
            $this->message = 'Geçersiz Kupon Kodu';
            return false;
        }

        if (!$this->getCouponCheckDate()) {
            $this->message = 'Kupon Geçerlilik Tarihi Sona Ermiştir.';
            return false;
        }

        if ($this->couponDB->user_id > 0 && $this->couponDB->user_id != $this->userId) {
            $this->message = 'Geçersiz Kupon Kodu';
            return false;
        }

        if ($this->couponDB->min_order_price > $this->basketRepository->subTotal()) {
            $this->message = 'Bu kuponda minimum sipariş tutarı ' . priceFormat($this->couponDB->min_order_price) . ' olmalıdır.';
            return false;
        }

        $time = now()->format('H:i:s');
        if ($this->couponDB->coupon_group_start_time && $this->couponDB->coupon_group_end_time) {
            if ($this->couponDB->coupon_group_start_time > $time || $this->couponDB->coupon_group_end_time < $time) {
                $startTime = now()->parse($this->couponDB->coupon_group_start_time)->format('H:i');
                $endTime = now()->parse($this->couponDB->coupon_group_end_time)->format('H:i');
                $this->message = 'Bu kupon ' . $startTime . ' - ' . $endTime . ' saatleri arasında kullanılabilir';
                return false;
            }
        }

        if (!$this->checkRestaurantCoupon($this->couponDB->coupon_group_id, $cartRestaurant->id)) {
            return false;
        }

        if (in_array($this->couponDB->code, ['HŞGLDN25']) || $this->couponDB->first_order === 1) {
            $totalOrder = Order::where('user_id', $this->userId)
                ->where('order_status_id', '>', 0)
                ->whereNotIn('orders.order_status_id', OrderStatus::ORDERCANCEL)
                ->count();
            if ($totalOrder > 0) {
                $this->message = "Kupon kodu ilk sipariş için geçerlidir.";
                return false;
            }
        }

        if ($this->couponDB->type == 'jenerik') {

            //1 kupon vardır.her kullanıcı kullanım sayısı kadar kullanabilir.
            $checkUserOrderCoupon = OrderCoupon::join('orders', 'orders.id', '=', 'order_coupons.order_id')
                ->where('coupon_id', $this->couponDB->id)
                ->where('orders.user_id', $this->userId)
                ->where('orders.order_status_id', '>', 0)
                ->whereNotIn('orders.order_status_id', OrderStatus::ORDERCANCEL)
                ->count();
            if ($checkUserOrderCoupon >= $this->couponDB->uses_quantity) {
                $this->message = "Üzgünüz Kupon daha önce kullanılmıştır.";
                return false;
            }
        } else if ($this->couponDB->type == 'unique') {

            //kuponu kullanım sayısı kadar kullanabilir.
            $checkUserOrderCoupon = OrderCoupon::join('orders', 'orders.id', '=', 'order_coupons.order_id')
                ->where('coupon_id', $this->couponDB->id)
                ->where('orders.user_id', $this->userId)
                ->where('orders.order_status_id', '>', 0)
                ->whereNotIn('orders.order_status_id', OrderStatus::ORDERCANCEL)
                ->count();
            if ($checkUserOrderCoupon >= $this->couponDB->uses_quantity) {
                $this->message = "Üzgünüz Kupon daha önce kullanılmıştır.";
                return false;
            }
        } else {
            $orderCoupon = OrderCoupon::join('orders', 'orders.id', '=', 'order_coupons.order_id')
                ->where('coupon_id', $this->couponDB->id)
                ->where('orders.order_status_id', '>', 0)
                ->whereNotIn('orders.order_status_id', OrderStatus::ORDERCANCEL)
                ->count();

            if ($orderCoupon >= $this->couponDB->uses_quantity) {
                $this->message = "Üzgünüz, bu kampanyadan faydalanma hakkınız bulunmuyor.";
                return false;
            }
        }

        //gruptaki kuponların kullanıcı bazlı max kullanım adedini belirleme
        if ($this->couponDB->coupon_group_uses_quantity > 0) {

            $checkUserOrderCoupon = OrderCoupon::join('orders', 'orders.id', '=', 'order_coupons.order_id')
                ->where('coupon_group_id', $this->couponDB->coupon_group_id)
                ->where('orders.user_id', $this->userId)
                ->where('orders.order_status_id', '>', 0)
                ->whereNotIn('orders.order_status_id', OrderStatus::ORDERCANCEL)
                ->count();
            if ($checkUserOrderCoupon >= $this->couponDB->coupon_group_uses_quantity) {
                $this->message = "Geçersiz Kupon Kodu";
                return false;
            }
        }

        $shippingCondition = \Cart::getCondition('Hizmet Bedeli');
        if ($this->couponDB->discount_type === 'courier' && !$shippingCondition) {
            return false;
        }

        return true;
    }

    private function checkRestaurantCoupon($couponGroupId, $restaurantId)
    {
        $couponGroup = CouponGroup::find($couponGroupId);
        $restaurantIds = $couponGroup->forRestaurants->pluck('restaurant_id')->toArray();

        if (count($restaurantIds) > 0) {
            if (in_array($restaurantId, $restaurantIds)) {
                return true;
            } else {
                $this->message = "Kupon restoran için geçerli değildir.";
                return false;
            }
        }

        return true;
    }

    private function getCoupon()
    {
        return CouponDB::select(
            'coupons.*',
            'coupon_groups.name AS coupon_group_name',
            'coupon_groups.start_time AS coupon_group_start_time',
            'coupon_groups.end_time AS coupon_group_end_time',
            'coupon_groups.max_discount',
            'coupon_groups.first_order',
            'coupon_groups.uses_quantity as coupon_group_uses_quantity'
        )
            ->join('coupon_groups', 'coupon_groups.id', '=', 'coupons.coupon_group_id')
            ->where('coupons.status', 1)
            ->where('coupons.code', $this->code)
            ->where('coupon_groups.status', 1)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('coupons.user_id', 0)
                        ->orWhere('coupons.user_id', $this->userId);
                });
            })->first();

    }

    private function getCouponCheckDate()
    {
        return CouponDB::select(
            'coupons.*',
            'coupon_groups.name AS coupon_group_name',
            'coupon_groups.start_time AS coupon_group_start_time',
            'coupon_groups.end_time AS coupon_group_end_time',
            'coupon_groups.first_order'
        )
            ->join('coupon_groups', 'coupon_groups.id', '=', 'coupons.coupon_group_id')
            ->where('coupons.status', 1)
            ->where('coupons.code', $this->code)
            ->where('coupon_groups.status', 1)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('coupons.start_date', null)
                        ->orWhereDate('coupons.start_date', '<=', Carbon::now()->format('Y-m-d'));
                })->where(function ($query) {
                    $query->where('coupons.end_date', null)
                        ->orWhereDate('coupons.end_date', '>=', Carbon::now()->format('Y-m-d'));
                });
            })->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('coupon_groups.start_date', null)
                        ->orWhereDate('coupon_groups.start_date', '<=', Carbon::now()->format('Y-m-d'));
                })->where(function ($query) {
                    $query->where('coupon_groups.end_date', null)
                        ->orWhereDate('coupon_groups.end_date', '>=', Carbon::now()->format('Y-m-d'));
                });
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('coupons.user_id', 0)
                        ->orWhere('coupons.user_id', $this->userId);
                });
            })->first();

    }

    public function getName()
    {
        return $this->name;
    }

    public function getKey()
    {
        return 'coupon';
    }

    public function getAmount()
    {

        $subTotal = $this->basketRepository->subTotal();

        if ($this->couponDB->discount_type == 'percent') {
            $discount = ($this->couponDB->discount * $subTotal) / 100;
        } elseif ($this->couponDB->discount_type == 'percent_courier') {
            $discount = ($this->couponDB->discount * $subTotal) / 100;
            $this->shippingFreeConditions();
        } elseif ($this->couponDB->discount_type == 'discount_courier') {
            $discount = $this->couponDB->discount;
            $this->shippingFreeConditions();
        } elseif ($this->couponDB->discount_type == 'courier') {
            $discount = 0;
            $shippingCondition = \Cart::getCondition('Hizmet Bedeli');
            $shippingFreeCondition = \Cart::getCondition('Restoran İndirimi(Ücretsiz Kurye)');
            if ($shippingCondition && !$shippingFreeCondition) {
                $this->shippingFreeConditions();
            }
        }

        if ($discount > $subTotal) {
            $discount = $subTotal;
        }

        if ($this->couponDB->max_discount && $discount > $this->couponDB->max_discount) {
            $discount = $this->couponDB->max_discount;
        }

        return -$discount;
    }

    private function shippingFreeConditions()
    {
        $shippingCondition = \Cart::getCondition('Hizmet Bedeli');
        $shippingFreeCondition = \Cart::getCondition('Restoran İndirimi(Ücretsiz Kurye)');
        \Cart::removeConditionsByType('discount_shipping');

        if (!$shippingCondition || $shippingFreeCondition) {
            return false;
        }

        //ücretsiz kurye indirimi var ise
        $freeDeliveryCondition=\Cart::getConditionsByType('free_delivery');
        foreach ($freeDeliveryCondition as $freeDelivery){
            if ($freeDelivery->getType()=='free_delivery') {
                return false;
            }
        }

        if ($shippingCondition->getValue() == 0) {
            return false;
        }

        $condition = new CartCondition([
            'name' => $this->name.' (Ücretsiz Teslimat)',
            'type' => 'discount_shipping',
            'target' => 'total',
            'value' => -$shippingCondition->getValue(),
            'order' => 8,
            'attributes' => [
                'type' => 'discount_shipping',
                'associatedModel' => $shippingCondition->getAttributes()['associatedModel'],
            ]
        ]);

        \Cart::condition($condition);
    }

    public function apply()
    {
        \Cart::removeConditionsByType($this->getKey());
        \Cart::removeConditionsByType('discount_shipping');

        if (!$this->isUses()) {
            return false;
        } else {

            $condition = new CartCondition([
                'name' => $this->getName(),
                'type' => $this->getKey(),
                'target' => 'total',
                'value' => $this->getAmount(),
                'order' => 3,
                'attributes' => [
                    'type' => 'coupon',
                    'associatedModel' => $this,
                    'couponDB' => $this->couponDB,
                    'code' => $this->code
                ]
            ]);

            \Cart::condition($condition);

            $this->message = "Tebrikler!\n Kupon indiriminiz uygulandı.\n\nBu siparişte hizmet bedeli dahil " . priceFormat($this->basketRepository->cartTotal()) . " ödeyeceksiniz.";

            return true;
        }
    }

    public function getMessage()
    {
        return $this->message;
    }
}
