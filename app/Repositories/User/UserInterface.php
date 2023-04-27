<?php


namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserInterface
{
    public function findById(int $id): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): ?User;

    public function remove(int $id): bool;

    public function paginate(int $limit): LengthAwarePaginator;

    public function all(): Collection;
}
