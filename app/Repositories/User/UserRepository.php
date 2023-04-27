<?php


namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update($id,array $data): User
    {
        User::where('id', $id)->update($data);
        return $this->findById($id);
    }

    public function remove(int $id): bool
    {
        return User::where('id', $id)->delete();
    }

    public function paginate($limit): LengthAwarePaginator
    {
        return User::filter(request())->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function all(): Collection
    {
        return User::select('users.*')->filter(request())->take(100)->orderBy('users.name')->get();
    }

}
