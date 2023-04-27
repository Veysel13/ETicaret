<?php


namespace App\Repositories\Product;


use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductInterface
{

    public function findById(int $id): ?Product;

    public function findByUpc(string $upc): ?Product;

    public function findBySku(string $sku): ?Product;

    public function create(array $data): Product;

    public function update(int $id, array $data): ?Product;

    public function remove(int $id): bool;

    public function paginate(int $limit): LengthAwarePaginator;

    public function all(): Collection;

    public function findByRestaurantIdFoodsSearch(int $restaurantId, string $term): Collection;

    public function findByIdProductRemove(int $id): bool;
}
