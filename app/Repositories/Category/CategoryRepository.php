<?php


namespace App\Repositories\Category;


use App\Model\Restaurant\CategoryFood;
use App\Models\Category;
use App\Models\CategoryProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryInterface
{

    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update($id,array $data): Category
    {
        Category::where('id', $id)->update($data);
        return $this->findById($id);
    }

    public function remove(int $id): bool
    {
        return Category::where('id', $id)->delete();
    }

    public function paginate($limit): LengthAwarePaginator
    {
        return Category::filter(request())->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function findByRestaurantIdCategoriesPaginate(int $restaurantId,int $limit): LengthAwarePaginator
    {
        return Category::select("categories.*")
            ->filter(request())
            ->where('categories.restaurant_id', $restaurantId)
            ->orderBy('sort', 'ASC')
            ->paginate($limit);
    }

    public function all(): Collection
    {
        return Category::select('brands.*')->filter(request())->orderBy('brands.name')->get();
    }

    public function foodCount(int $id): int
    {
        return CategoryProduct::join('products', 'products.id', '=', 'category_products.product_id')->filter(request())->where('category_id', $id)->count();
    }

    public function products(int $id): Collection
    {
        return CategoryProduct::where('category_id', $id)
            ->join('products', 'products.id', '=', 'category_products.product_id')
            ->orderBy('products.sort', 'ASC')
            ->filter(request())
            ->get();
    }

    public function findByIdProductRemove(int $id): bool
    {
        return CategoryProduct::where('category_id', $id)->delete();
    }

    public function productCreate(array $data): CategoryProduct
    {
        return CategoryProduct::create($data);
    }
}
