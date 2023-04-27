<?php

namespace App\Http\Controllers\Backend\Dasboard;

use App\Constants\AuthorityType;
use App\Constants\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Brand\Brand;
use App\Models\Mission\OrderTarget;
use App\Models\Order\SaleOrder;
use App\Models\Order\SaleOrderHistory;
use App\Models\Order\SaleOrderItem;
use App\Models\Order\SaleOrderReceivedItem;
use App\Models\Product\AmazonProduct;
use App\Models\Product\Product;
use App\Models\Store\Store;
use App\Models\User\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DasboardController extends Controller
{

    public function index(Request $request)
    {
        $blade=[];

        return  redirect()->route('backend.restaurant');

        return view('backend.dashboard.index', $blade);
    }

}
