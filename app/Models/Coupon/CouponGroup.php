<?php

namespace App\Models\Coupon;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CouponGroup extends Model
{
    protected $fillable = [
        'status',
        'name',
        'description',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'max_discount',
        'first_order'
    ];

    public function forRestaurants()
    {
        return $this->hasMany(CouponForRestaurant::class, 'coupon_group_id');
    }

    public function coupons()
    {
        return $this->hasMany(CouponDB::class, 'coupon_group_id');
    }

    public function getBgImageUrlAttribute(): string
    {
        return \Storage::disk('uploads')->url($this->bg_image);
    }

    public function scopeFilter($query, Request $request): Builder
    {
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        if ($request->filled('condition_name')) {
            $query->where('condition_name', 'LIKE', '%' . $request->input('condition_name') . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', (bool)$request->input('status'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', now()->parse($request->input('start_date'))->format('Y-m-d'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', now()->parse($request->input('start_date'))->format('Y-m-d'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', now()->parse($request->input('end_date'))->format('Y-m-d'));
        }

        if ($request->filled('coupon_group_date')) {
            $query->where(function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('start_date', null)
                        ->orWhereDate('start_date', '<=', now()->parse($request->input('coupon_group_date'))->format('Y-m-d'));
                })->where(function ($query) use ($request) {
                    $query->where('end_date', null)
                        ->orWhereDate('end_date', '>=', now()->parse($request->input('coupon_group_date'))->format('Y-m-d'));
                });
            });
        }

        return $query;
    }

}
