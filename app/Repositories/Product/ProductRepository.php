<?php


namespace App\Repositories\Product;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductInterface
{

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function findByUpc(string $upc): ?Product
    {
        return Product::where('upc',$upc)->first();
    }

    public function findBySku(string $sku): ?Product
    {
        return Product::where('sku',$sku)->first();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update($id,array $data): Product
    {
        Product::where('id', $id)->update($data);
        return $this->findById($id);
    }

    public function remove(int $id): bool
    {
        return Product::where('id', $id)->delete();
    }

    public function paginate($limit): LengthAwarePaginator
    {
        return Product::select('products.*')->filter(request())->orderBy('products.created_at', 'DESC')->paginate($limit);
    }

    public function all(): Collection
    {
        return Product::select('products.*')->filter(request())->take(100)->orderBy('products.created_at')->get();
    }

    public function findByRestaurantIdFoodsSearch(int $restaurantId, string $term): Collection
    {
        return Product::where('name', 'LIKE', '%' . $term . '%')
            ->where('restaurant_id', $restaurantId)
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function findByIdProductRemove(int $id): bool
    {
        return CategoryProduct::where('product_id', $id)->delete();
    }
}
