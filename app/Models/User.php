<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable,SoftDeletes;

    protected $table="users";

    protected $fillable = [
        'fullname', 'email', 'password','activation_key',"status",'type'
    ];


    protected $hidden = [
        'password', 'activation_key',
    ];

    public function getGroupsArrAttribute()
    {

        return [];
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function detail(){
        return $this->hasOne('App\Models\UserDetail',"user_id");
    }

    public function scopeFilter($query, Request $request): Builder
    {
        if ($request->filled('id')) {
            $query->where('users.id',$request->input('id'));
        }

        if ($request->filled('fullname')) {
            $query->where('users.fullname', 'LIKE', '%' . $request->input('fullname') . '%');
        }

        if ($request->filled('status')) {
            $query->where('users.status',$request->input('status'));
        }

        return  $query;
    }
}
