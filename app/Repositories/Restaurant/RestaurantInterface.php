<?php


namespace App\Repositories\Restaurant;


use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RestaurantInterface
{

    public function findById(int $id): ?Restaurant;

    public function create(array $data): Restaurant;

    public function update(int $id, array $data): ?Restaurant;

    public function remove(int $id): bool;

    public function paginate(int $limit): LengthAwarePaginator;

    public function relationPaginate(int $limit): LengthAwarePaginator;

    public function all(): Collection;
}
