<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderCoupon extends Model
{
    protected $fillable = [
        'order_id',
        'coupon_group_id',
        'coupon_id',
        'condition_name',
        'condition_key',
        'coupon_group_name',
        'discount_type',
        'discount',
        'start_date',
        'end_date',
        'min_order_price',
        'uses_quantity',
        'user_id',
        'email',
        'gsm',
        'code',
        'price',
    ];
}
