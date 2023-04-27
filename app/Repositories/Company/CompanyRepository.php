<?php


namespace App\Repositories\Company;

use App\Models\Brand\Brand;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository implements CompanyInterface
{

    public function findById(int $id): ?Company
    {
        return Company::find($id);
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update($id,array $data): Company
    {
        Company::where('id', $id)->update($data);
        return $this->findById($id);
    }

    public function remove(int $id): bool
    {
        return Company::where('id', $id)->delete();
    }

    public function paginate($limit): LengthAwarePaginator
    {
        return Company::filter(request())->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function all(): Collection
    {
        return Company::select('companies.*')->filter(request())->take(100)->orderBy('companies.name')->get();
    }
}
