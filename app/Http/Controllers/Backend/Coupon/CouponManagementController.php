<?php

namespace App\Http\Controllers\Backend\Coupon;

use App\Constants\OrderStatus;
use App\Http\Controllers\Controller;
use App\Libraries\Redis\CouponRedis;
use App\Models\Coupon\CouponDB;
use App\Models\Coupon\CouponForRestaurant;
use App\Models\Coupon\CouponGroup;
use App\Models\Order\OrderCoupon;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponManagementController extends Controller
{
    public function index(Request $request)
    {
        $blade = [];
        $blade['pageTitle'] = 'Coupon Group List';

        return view('backend.coupon.group.index', $blade);
    }

    public function xhrIndex(Request $request)
    {

        $limit = $request->has('length') ? intval($request->input('length')) : 20;
        $couponGroup = CouponGroup::filter(request())->orderBy('id', 'desc')->paginate($limit);

        $couponGroup->map(function ($item) {
            if ($item->status) {
                $item->listStatus = '<span class="label label-success">Aktif</span>';
            } else {
                $item->listStatus = '<span class="label label-danger">Pasif</span>';
            }

            $item->couponCount = $item->coupons->count();
            $item->restaurantsCount = $item->forRestaurants->count();

            $item->usedCoupon = OrderCoupon::join('orders', 'order_coupons.order_id', 'orders.id')
                ->where('order_coupons.coupon_group_id', $item->id)
                ->where('orders.order_status_id', '>', 0)
                ->whereNotIn('orders.order_status_id', OrderStatus::ORDERCANCEL)
                ->count();

            $item->createCoupon = route('backend.couponManagement.createCoupon', $item->id);
            $item->detailUrl = route('backend.couponManagement.showGroup', $item->id);
            $item->editUrl = route('backend.couponManagement.editGroup', $item->id);
            $item->removeUrl = route('backend.couponManagement.removeGroup', $item->id);
            $item->restaurantUrl = route('backend.couponManagement.restaurant', $item->id);

            return $item;
        });

        $blade['draw'] = $request->input('draw');
        $blade['recordsTotal'] = $couponGroup->total();
        $blade['recordsFiltered'] = $couponGroup->total();
        $blade['data'] = $couponGroup->toArray()['data'];
        return response()->json($blade);
    }

    public function createGroup()
    {
        $data = [];
        return view('backend.coupon.group.create', $data);
    }

    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $couponGroupData = [
            'name' => $request->input('name'),
            'status' => $request->input('status', 0),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'first_order' => $request->input('first_order', 0),
            'max_discount' => $request->input('max_discount', 0)
        ];

        $couponGroup = CouponGroup::create($couponGroupData);

        if ($request->input('restaurant_ids') && $request->input('restaurant_filter') == 'restaurant_filter') {
            $restaurantIds = collect(explode(',', $request->post('restaurant_ids')))->map(function ($id) {
                $item['restaurant_id'] = $id;
                return $item;
            });
            $couponGroup->forRestaurants()->createMany($restaurantIds);
        }

        $couponRedis = new CouponRedis();
        $redisData = $couponGroup->toArray();
        $redisData['restaurant_ids'] = $couponGroup->forRestaurants->pluck('restaurant_id')->toArray();
        $couponRedis->setCouponGroup($redisData);

        session()->flash('success', 'Grup Eklendi');
        return response()->json([
            'redirectUrl' => route('backend.couponManagement.index')
        ]);
    }

    public function showGroup(Request $request, $couponGroupId)
    {
        if ($request->ajax()) {

            $query = CouponDB::select('coupons.*')
                ->where('coupons.coupon_group_id', $couponGroupId)
                ->leftJoin('order_coupons', 'coupons.id', 'order_coupons.coupon_id');

            if ($request->filled('list')) {
                if ($request->get('list') == 'used') {
                    $query->whereNotNull('order_coupons.id');
                } else {
                    $query->whereNull('order_coupons.id');
                }
            }

            $coupons = $query->orderBy('id', 'desc')->groupBy('coupons.id')->paginate($request->input('limit', 20));

            $coupons->map(function ($item) {
                $item->user = "";

                $item->couponType = 'Normal';
                if ($item->type == "jenerik") {
                    $item->couponType = 'Jenerik';
                } else if ($item->type == "unique") {
                    $item->couponType = 'Unique';
                } else if ($item->type == "txtFile") {
                    $item->couponType = 'Özel';
                }

                if ($item->discount_type == "percent") {
                    $item->discountType = "Yüzde";
                    $item->discountList = "%" . (int)$item->discount;
                } elseif ($item->discount_type == "percent_courier") {
                    $item->discountType = "Yüzde + Ücretsiz Kurye";
                    $item->discountList = "%" . (int)$item->discount;
                } elseif ($item->discount_type == "discount_courier") {
                    $item->discountType = "İndirim + Ücretsiz Kurye";
                    $item->discountList = (int)$item->discount . " TL";
                } elseif ($item->discount_type == "courier") {
                    $item->discountType = "Ücretsiz Kurye";
                    $item->discountList = "Ücretsiz Kurye";
                } else {
                    $item->discountType = "İndirim";
                    $item->discountList = (int)$item->discount . " TL";
                }

                if ($item->status) {
                    $item->listStatus = '<span class="label label-success">Aktif</span>';
                } else {
                    $item->listStatus = '<span class="label label-danger">Pasif</span>';
                }

                $item->used_quantity = OrderCoupon::join('orders', 'order_coupons.order_id', 'orders.id')
                    ->where('order_coupons.coupon_id', $item->id)
                    ->where('orders.order_status_id', '>', 0)
                    ->whereNotIn('orders.order_status_id', OrderStatus::ORDERCANCEL)
                    ->count();

                $item->minOrderPrice = priceFormat($item->min_order_price);

                $item->removeUrl = route('backend.couponManagement.removeCoupon', $item->id);
                return $item;
            });

            $blade['draw'] = $request->input('draw');
            $blade['recordsTotal'] = $coupons->total();
            $blade['recordsFiltered'] = $coupons->total();
            $blade['data'] = $coupons->toArray()['data'];
            return response()->json($blade);
        }

        $couponGroup = CouponGroup::find($couponGroupId);
        $blade['couponGroup'] = $couponGroup;
        $blade['pageTitle'] = $couponGroup->name . ' Coupon';

        return view('backend.coupon.index', $blade);
    }

    public function editGroup($couponGroupId)
    {
        $couponGroup = CouponGroup::find($couponGroupId);
        $data = [
            'couponGroup' => $couponGroup,
            'pageTitle' => $couponGroup->name . ' Edit',
            'selectedRestaurantIds' => $couponGroup->forRestaurants->pluck('restaurant_id')->toArray(),
        ];

        return view('backend.coupon.group.edit', $data);
    }

    public function updateGroup(Request $request, $couponGroupId)
    {
        $couponGroup = CouponGroup::find($couponGroupId);
        if (!$couponGroup) {
            return response()->json([
                'status' => false,
                'errors' => [['Coupon Group Not Found']]
            ], 500);
        }

        $request->validate([
            'name' => 'required',
        ]);

        $couponGroupData = [
            'name' => $request->input('name'),
            'status' => $request->input('status', 0),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'max_discount' => $request->input('max_discount'),
            'first_order' => $request->input('first_order', 0),
        ];

        $couponGroup->update($couponGroupData);

        $couponRedis = new CouponRedis();
        $redisData = $couponGroup->toArray();
        $redisData['restaurant_ids'] = $couponGroup->forRestaurants->pluck('restaurant_id')->toArray();
        $couponRedis->setCouponGroup($redisData);

        session()->flash('success', 'Group Update');
        return response()->json([
            'redirectUrl' => route('backend.couponManagement.index')
        ]);
    }

    public function restaurant(Request $request, $id)
    {
        $couponGroup = CouponGroup::where('id', $id)->first();
        if (!$couponGroup) {
            session()->flash('errors', 'Coupon Group Not Found');
            return redirect()->back();
        }

        $blade = [];
        $blade['couponGroup'] = $couponGroup;
        $blade['pageTitle'] = $couponGroup->name;

        $restaurants = CouponForRestaurant::select(
            'restaurants.id as id',
            'restaurants.name as name',
            'restaurants.status'
        )->where('coupon_group_id', $couponGroup->id)
            ->join('restaurants', 'restaurants.id', '=', 'coupon_for_restaurants.restaurant_id')
            ->orderBy('restaurants.name', 'ASC')
            ->get();

        $restaurants->map(function ($item) {

            return $item;
        });

        $blade['restaurants'] = $restaurants->toArray();

        return view('backend.coupon.group.restaurant', $blade);
    }

    public function restaurantUpdate(Request $request, $id)
    {
        $couponGroup = CouponGroup::where('id', $id)->first();
        if (!$couponGroup) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon Not Found'
            ]);
        }

        $request->validate([
            'restaurant_ids' => 'required',
        ]);

        $dataIds = $request->input('restaurant_ids', []);
        $dataIds = array_unique($dataIds);

        CouponForRestaurant::where('coupon_group_id', $couponGroup->id)->delete();
        foreach ($dataIds as $dataId) {
            CouponForRestaurant::create([
                'restaurant_id' => $dataId,
                'coupon_group_id' => $couponGroup->id,
            ]);
        }

        $couponRedis = new CouponRedis();
        $redisData = $couponGroup->toArray();
        $redisData['restaurant_ids'] = $couponGroup->forRestaurants->pluck('restaurant_id')->toArray();
        $couponRedis->setCouponGroup($redisData);

        return response()->json([
            'status' => true,
            'message' => 'Coupon Group Update',
            'redirectUrl' => route('backend.couponManagement.index')
        ]);
    }

    public function removeGroup($couponGroupId)
    {
        CouponGroup::where('id', $couponGroupId)->delete();
        CouponDB::where('coupon_group_id', $couponGroupId)->delete();
        CouponForRestaurant::where('coupon_group_id', $couponGroupId)->delete();

        $couponRedis = new CouponRedis();
        $couponRedis->removeCouponGroup($couponGroupId);

        return response()->json([
            'status' => true,
            'message' => 'Grup Silindi'
        ]);
    }

    public function createCoupon($couponGroupId)
    {
        $blade['couponGroup'] = CouponGroup::find($couponGroupId);
        $blade['pageTitle'] = 'New Coupon Add';

        return view('backend.coupon.create', $blade);
    }

    public function storeCoupon(Request $request, $couponGroupId)
    {
        set_time_limit(0);
        $request->validate([
            'coupon_count' => 'required|numeric|min:1',
            'code_length' => 'required|numeric|min:0|max:20',

            'discount_type' => 'required',
            'discount' => 'numeric|min:0',

            'min_order_price' => 'numeric|min:0',
            'uses_quantity' => 'numeric|min:0',
        ]);

        $coupon_type = $request->input('coupon_type');

        if ($coupon_type === 'txtFile') {
            $request->validate([
                'coupon_prefix' => 'required',
                'txtFile' => 'required|mimes:txt'
            ]);
        }

        $coupon_count = (int)$request->input('coupon_count') ?? 1;
        $coupon_prefix = trim($request->input('coupon_prefix'));
        $code_length = (int)$request->input('code_length') ?? 4;

        $discount = (int)$request->input('discount');
        $discount_type = $request->input('discount_type');

        if ($request->input('courier') && $discount_type != "courier")
            $discount_type .= "_courier";

        $couponRedis = new CouponRedis();

        if ($coupon_type === 'txtFile') {

            $file = $request->file('txtFile');

            $contents = \Illuminate\Support\Facades\File::get($file->getRealPath());
            $expContents = explode(";", $contents);
            $expContents = array_map('trim', $expContents);
            $expContents = array_filter($expContents, 'intval');
            $expContents = array_unique($expContents);
            $prefixCodeUserIds = array_map(function ($expContent) use ($coupon_prefix, $couponGroupId) {
                return Str::upper($coupon_prefix . $couponGroupId . $expContent);
            }, $expContents);

            $oldCodes = [];
            $getCoupons = CouponDB::select('code')->whereIn('code', $prefixCodeUserIds)->get();
            if ($getCoupons->count() > 0) {
                $oldCodes = array_map(function ($getCoupon) {
                    return $getCoupon['code'];
                }, $getCoupons->toArray());
            }

            foreach ($expContents as $userId) {

                $userId = intval($userId);
                $code = Str::upper($coupon_prefix . $couponGroupId . $userId);

                if (in_array($code, $oldCodes) !== false) {
                    continue;
                }

                $store = [
                    'code' => $code,
                    'type' => $coupon_type,
                    'coupon_group_id' => $couponGroupId,
                    'discount_type' => $discount_type,
                    'discount' => $discount,
                    'min_order_price' => $request->input('min_order_price'),
                    'uses_quantity' => $request->input('uses_quantity'),
                    'user_id' => $userId,
                    'status' => 1,
                ];

                $couponDB = CouponDB::create($store);
                $couponRedis->setUserCoupon($userId, $couponDB->toArray());
            }

            return redirect()
                ->route('backend.couponManagement.showGroup', $couponGroupId)
                ->with('success', 'Kupon Oluşturuldu');
        }

        // Random Code
        $couponCodes = [];
        $count_generate = $coupon_count;
        while (true) {
            for ($i = 0; $i < $count_generate; $i++) {
                if (empty($coupon_prefix)) {
                    $code = Str::upper(Str::random($code_length));
                } else {
                    $code = Str::upper($coupon_prefix . Str::random($code_length));
                }
                $couponCodes[$code] = $code;
                $couponCodes = array_unique($couponCodes);
            }

            $checkCoupons = CouponDB::select('code')->whereIn('code', $couponCodes)->get();
            foreach ($checkCoupons as $checkCoupon) {
                unset($couponCodes[$checkCoupon->code]);
            }
            $count_generate = $count_generate - $checkCoupons->count();

            if (count($couponCodes) >= $coupon_count) {
                break;
            }
        }
        $generateCodes = array_values($couponCodes);

        for ($x = 0; $x < $coupon_count; $x++) {
            $code = $generateCodes[$x];

            $codesData = [
                'code' => $code,
                'type' => $coupon_type,
                'coupon_group_id' => $couponGroupId,
                'discount_type' => $discount_type,
                'discount' => $discount,
                'min_order_price' => $request->input('min_order_price'),
                'uses_quantity' => $request->input('uses_quantity'),
                'user_id' => 0,
                'status' => 1,
                'is_notification' => 0,
            ];

            $couponDB = CouponDB::create($codesData);
            $couponRedis->setUserCoupon(0, $couponDB->toArray());
        }

        return redirect()
            ->route('backend.couponManagement.showGroup', $couponGroupId)
            ->with('success', 'Coupon Add');
    }

    public function removeCoupon($couponId)
    {
        $coupon = CouponDB::find($couponId);

        $couponRedis = new CouponRedis();
        $couponRedis->removeUserCoupon($coupon->user_id, $coupon->id);

        $coupon->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kupon Silindi'
        ]);
    }

    public function xhrRestaurantFilter(Request $request)
    {
        $restaurants = Restaurant::select("restaurants.name", "restaurants.id", 'restaurants.status')
            ->filter(request())
            ->where('restaurants.status', 1);

        if ($request->input('couponGroupId')) {
            $restaurants = $restaurants->leftJoin('coupon_for_restaurants', function ($join) use ($request) {
                $join->on('coupon_for_restaurants.restaurant_id', '=', 'restaurants.id')
                    ->where('coupon_group_id', $request->input('couponGroupId'));
            })->whereNull('coupon_for_restaurants.restaurant_id');
        }

        $restaurants = $restaurants->paginate($request->input('length', 20));

        $blade['draw'] = $request->input('draw');
        $blade['recordsTotal'] = $restaurants->total();
        $blade['recordsFiltered'] = $restaurants->total();
        $blade['data'] = $restaurants->toArray()['data'];
        return response()->json($blade);
    }
}
