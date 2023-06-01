<?php

namespace App\Models\Coupon;

use Illuminate\Database\Eloquent\Model;

class CouponForRestaurant extends Model
{
    protected $fillable = [
        'restaurant_id',
        'coupon_group_id'
    ];
}
