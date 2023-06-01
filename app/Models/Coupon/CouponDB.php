<?php

namespace App\Models\Coupon;

use Illuminate\Database\Eloquent\Model;

class CouponDB extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'type',
        'status',
        'coupon_group_id',
        'discount_type',
        'discount',
        'min_order_price',
        'uses_quantity',
        'user_id',
        'code',
        'is_notification'
    ];
}
