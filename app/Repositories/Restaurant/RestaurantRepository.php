<?php


namespace App\Repositories\Restaurant;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RestaurantRepository implements RestaurantInterface
{

    public function findById(int $id): ?Restaurant
    {
        return Restaurant::find($id);
    }

    public function create(array $data): Restaurant
    {
        return Restaurant::create($data);
    }

    public function update($id,array $data): Restaurant
    {
        Restaurant::where('id', $id)->update($data);
        return $this->findById($id);
    }

    public function remove(int $id): bool
    {
        return Restaurant::where('id', $id)->delete();
    }

    public function paginate($limit): LengthAwarePaginator
    {
        return Restaurant::filter(request())->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function relationPaginate($limit): LengthAwarePaginator
    {
        return Restaurant::select('restaurants.*')->filter(request())->orderBy('restaurants.created_at', 'DESC')->paginate($limit);
    }

    public function all(): Collection
    {
        return Restaurant::select('restaurants.*')->filter(request())->take(100)->orderBy('restaurants.created_at')->get();
    }

}
