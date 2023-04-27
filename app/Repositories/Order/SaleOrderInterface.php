<?php


namespace App\Repositories\Order;


use App\Models\Order\PreSaleOrder;
use App\Models\Order\SaleOrder;
use App\Models\Order\SaleOrderHistory;
use App\Models\Order\SaleOrderItem;
use App\Models\Order\SaleOrderReceivedItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface SaleOrderInterface
{

    public function findById(int $id): ?SaleOrder;

    public function findByBarcode(string $id): ?SaleOrder;

    public function create(array $data): SaleOrder;

    public function update(int $id, array $data): ?SaleOrder;

    public function remove(int $id): bool;

    public function paginate(int $limit): LengthAwarePaginator;

    public function relationPaginate(int $limit,$column,$desc): LengthAwarePaginator;

    public function all(): Collection;

    public function createItem(array $data): SaleOrderItem;

    public function orderHistory($id): Collection;

    public function createHistory(array $data): SaleOrderHistory;

    public function createReceivedItem(array $data): SaleOrderReceivedItem;

    public function findByOrderIdPreOrder(int $id): ?PreSaleOrder;
}
