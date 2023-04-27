<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Restaurant extends Model
{

    use SoftDeletes;
    protected $table="restaurants";

    protected $guarded=[];

    public function getLogoUrlAttribute()
    {
        if ($this->logo !== '' && $this->logo) {
            return \Storage::disk('uploads')->url($this->logo);
        }
        return null;
    }

    public function scopeFilter($query, Request $request): Builder
    {
        if ($request->filled('id')) {
            $query->where('restaurants.id',$request->input('id'));
        }

        if ($request->filled('name')) {
            $query->where('restaurants.name', 'LIKE', '%' . $request->input('name') . '%');
        }


        if ($request->filled('status')) {
            $query->where('restaurants.status',$request->input('status'));
        }


        return  $query;
    }
}
