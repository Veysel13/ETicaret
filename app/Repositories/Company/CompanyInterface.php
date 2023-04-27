<?php


namespace App\Repositories\Company;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyInterface
{

    public function findById(int $id): ?Company;

    public function create(array $data): Company;

    public function update(int $id, array $data): ?Company;

    public function remove(int $id): bool;

    public function paginate(int $limit): LengthAwarePaginator;

    public function all(): Collection;
}
