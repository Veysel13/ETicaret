<?php


namespace App\Repositories\Category;


use App\Models\Brand\Brand;
use App\Models\Category;
use App\Models\CategoryProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryInterface
{

    public function findById(int $id): ?Category;

    public function create(array $data): Category;

    public function update(int $id, array $data): ?Category;

    public function remove(int $id): bool;

    public function paginate(int $limit): LengthAwarePaginator;

    public function findByRestaurantIdCategoriesPaginate(int $restaurantId,int $limit): LengthAwarePaginator;

    public function all(): Collection;

    public function foodCount(int $id): int;

    public function products(int $id): Collection;

    public function findByIdProductRemove(int $id): bool;

    public function productCreate(array $data): CategoryProduct;
}
