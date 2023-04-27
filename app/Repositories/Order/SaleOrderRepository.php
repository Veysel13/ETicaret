<?php


namespace App\Repositories\Order;


use App\Constants\AuthorityType;
use App\Models\Order\PreSaleOrder;
use App\Models\Order\SaleOrder;
use App\Models\Order\SaleOrderHistory;
use App\Models\Order\SaleOrderItem;
use App\Models\Order\SaleOrderReceivedItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SaleOrderRepository implements SaleOrderInterface
{

    public function findById(int $id): ?SaleOrder
    {
        return SaleOrder::find($id);
    }

    public function findByBarcode(string $barcode): ?SaleOrder
    {
        return SaleOrder::where('receipt_barcode',$barcode)->first();
    }

    public function create(array $data): SaleOrder
    {
        return SaleOrder::create($data);
    }

    public function update($id,array $data): SaleOrder
    {
        SaleOrder::where('id', $id)->update($data);
        return $this->findById($id);
    }

    public function remove(int $id): bool
    {
        return SaleOrder::where('id', $id)->delete();
    }

    public function paginate($limit): LengthAwarePaginator
    {
        return SaleOrder::select('sale_orders.*')->filter(request())->orderBy('sale_orders.created_at', 'DESC')->paginate($limit);
    }

    public function relationPaginate($limit,$column,$desc): LengthAwarePaginator
    {
        if (auth('backend')->user()->is_admin == 1 || array_intersect([AuthorityType::ALLORDERLIST,], auth('backend')->user()->groupsArr))
        return SaleOrder::select('sale_orders.*')->filter(request())->orderBy($column, $desc)->with('store')->with('user')->paginate($limit);
        else
        return SaleOrder::select('sale_orders.*')->filter(request())->where('user_id',auth('backend')->user()->id)->orderBy($column, $desc)->with('store')->with('user')->paginate($limit);
    }

    public function all(): Collection
    {
        return SaleOrder::select('sale_orders.*')->filter(request())->orderBy('sale_orders.created_at','DESC')->get();
    }

    public function createItem(array $data): SaleOrderItem
    {
        return SaleOrderItem::create($data);
    }

    public function orderHistory($id): Collection
    {
        return SaleOrderHistory::select('*')->where('order_id',$id)->orderBy('created_at','DESC')->with('user')->get();
    }

    public function createHistory(array $data): SaleOrderHistory
    {
        return SaleOrderHistory::create($data);
    }

    public function createReceivedItem(array $data): SaleOrderReceivedItem
    {
        return SaleOrderReceivedItem::create($data);
    }

    public function findByOrderIdPreOrder(int $id): ?PreSaleOrder
    {
        return PreSaleOrder::where('order_id',$id)->orderBy('created_at','desc')->first();
    }
}
