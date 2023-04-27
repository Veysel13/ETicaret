<?php

namespace App\Repositories\Announcement;

use App\Models\Announcement\Announcement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AnnouncementInterface
{
    public function findById(int $id): ?Announcement;

    public function create(array $data): Announcement;

    public function update(int $id, array $data): ?Announcement;

    public function remove(int $id): bool;

    public function paginate(int $limit): LengthAwarePaginator;

    public function userPaginate(int $user_id,int $limit): LengthAwarePaginator;

    public function sentPaginate(int $user_id,int $limit): LengthAwarePaginator;

    public function all(): Collection;
}
