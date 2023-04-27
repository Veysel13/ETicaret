<?php

namespace App\Repositories\Announcement;

use App\Models\Announcement\Announcement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AnnouncementRepository implements AnnouncementInterface
{
    public function findById(int $id): ?Announcement
    {
        return Announcement::find($id);
    }

    public function create(array $data): Announcement
    {
        return Announcement::create($data);
    }

    public function update($id,array $data): Announcement
    {
        Announcement::where('id', $id)->update($data);
        return $this->findById($id);
    }

    public function remove(int $id): bool
    {
        return Announcement::where('id', $id)->delete();
    }

    public function paginate($limit): LengthAwarePaginator
    {
        return Announcement::filter(request())->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function userPaginate(int $user_id,int $limit): LengthAwarePaginator
    {
        return Announcement::where('receiver_id',$user_id)->filter(request())->with('senderUser')->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function sentPaginate(int $user_id,int $limit): LengthAwarePaginator
    {
        return Announcement::where('sender_id',$user_id)->filter(request())->with('receiverUser')->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function all(): Collection
    {
        return Announcement::select('announcements.*')->filter(request())->orderBy('announcements.created_at')->get();
    }
}
