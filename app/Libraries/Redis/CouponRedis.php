<?php

namespace App\Libraries\Redis;

use App\Models\Coupon\CouponDB;
use Carbon\Carbon;

class CouponRedis
{
    protected $redis;

    public function __construct()
    {
        $this->redis = app('redis');
    }

    public function getUserCouponsKey($userId)
    {
        return 'user_coupons:' . $userId;
    }

    public function setUserCoupon($userId, $coupon)
    {
        $userCoupons = $this->getUserCoupons($userId);
        $userCoupons[$coupon['id']] = $coupon;
        $this->redis->set($this->getUserCouponsKey($userId), json_encode($userCoupons));
        return $userCoupons;
    }

    public function getUserCoupons($userId)
    {
        $userCoupons = $this->redis->get($this->getUserCouponsKey($userId));
        return @json_decode($userCoupons, true) ?? [];
    }

    public function getUserCoupon($userId, $couponId)
    {
        $userCoupons = $this->getUserCoupons($userId);
        $userCoupons = @json_decode($userCoupons, true) ?? [];
        return $userCoupons[$couponId] ?? [];
    }

    public function removeUserCoupon($userId, $couponId)
    {
        $userCoupons = $this->getUserCoupons($userId);
        unset($userCoupons[$couponId]);
        $this->redis->set($this->getUserCouponsKey($userId), json_encode($userCoupons));
        return $userCoupons;
    }

    public function getCouponGroupKey()
    {
        return 'user_coupon_groups';
    }

    public function setCouponGroup($couponGroup)
    {
        $userCouponGroups = $this->getCouponGroups();
        $userCouponGroups[$couponGroup['id']] = $couponGroup;
        $this->redis->set($this->getCouponGroupKey(), json_encode($userCouponGroups));
        return $userCouponGroups;
    }

    public function getCouponGroups()
    {
        $couponGroups = $this->redis->get($this->getCouponGroupKey());
        return @json_decode($couponGroups, true) ?? [];
    }

    public function getCouponGroup($couponGroupId)
    {
        $couponGroups = $this->getCouponGroups();
        return $couponGroups[$couponGroupId] ?? [];
    }

    public function removeCouponGroup($couponGroupId)
    {
        $couponGroups = $this->getCouponGroups();
        unset($couponGroups[$couponGroupId]);
        $this->redis->set($this->getCouponGroupKey(), json_encode($couponGroups));
        return $couponGroups;
    }

    public function getActiveCoupons()
    {
        $userId = auth('api')->check() ? auth('api')->id() : 0;
        if ($userId === 0) {
            return false;
        }

        $activeCouponGroups = [];
        $getCouponGroups = $this->getCouponGroups();
        foreach ($getCouponGroups as $getCouponGroup) {
            if (@$getCouponGroup['status'] == 1 &&
                (
                    (@$getCouponGroup['start_date'] == null || @$getCouponGroup['start_date'] <= Carbon::now()->format('Y-m-d')) &&
                    (@$getCouponGroup['end_date'] == null || @$getCouponGroup['end_date'] >= Carbon::now()->format('Y-m-d'))
                )
            ) {
                $activeCouponGroups[] = $getCouponGroup;
            }
        }

        if (!$activeCouponGroups) {
            return false;
        }


        $activeCoupons = [];
        foreach ($activeCouponGroups as $activeCouponGroup) {

            $getUserCoupons = $this->getUserCoupons($userId);
            foreach ($getUserCoupons as $getUserCoupon) {
                if ($getUserCoupon['coupon_group_id'] == $activeCouponGroup['id']) {
                    if ((
                        (@$getUserCoupon['start_date'] == null || @$getUserCoupon['start_date'] <= Carbon::now()->format('Y-m-d')) &&
                        (@$getUserCoupon['end_date'] == null || @$getUserCoupon['end_date'] >= Carbon::now()->format('Y-m-d'))
                    )) {
                        $activeCoupons[] = [
                            'couponGroup' => $activeCouponGroup,
                            'coupon' => $getUserCoupon
                        ];
                    }
                }
            }
        }

        if(!$activeCoupons){
            return false;
        }

        return $activeCoupons;
    }

    public function getActiveCouponPopup()
    {
        $activeCoupons = $this->getActiveCoupons();
        if (!$activeCoupons) {
            return false;
        }

        $fullName = auth('api')->check() ? auth('api')->user()->fullname : '';

        $results = [];
        foreach ($activeCoupons as $activeCoupon) {
            if (!isset($activeCoupon['coupon']['is_notification']) || $activeCoupon['coupon']['is_notification'] == 0) {
                $results['title'] = 'Sayın ' . $fullName;
                $results['message'] = 'Hesabınıza ' . $activeCoupon['couponGroup']['name'] . ' kuponu tanımlanmıştır.' . "\n" . strip_tags(nl2br($activeCoupon['couponGroup']['description'])) . "\n\n" . 'Detaylar için Kuponlarım sayfasını ziyaret edebilirsiniz.';

                CouponDB::where('id', $activeCoupon['coupon']['id'])->update([
                    'is_notification' => 1
                ]);

                $activeCoupon['coupon']['is_notification'] = 1;
                $this->setUserCoupon($activeCoupon['coupon']['user_id'], $activeCoupon['coupon']);
                break;
            }
        }

        return $results;
    }


}
