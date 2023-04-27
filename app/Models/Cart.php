<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use SoftDeletes;
    protected $table="carts";

    public $guarded=[];

    public function detail(){
        return $this->hasMany('App\Models\CartProduct',"cart_id");
    }

    public function order(){
        return $this->hasOne('App\Models\Order');
    }

    public function aktif_sepet(){
        $aktif_sepet=DB::table("carts as s")
            ->leftjoin("siparis as si","si.sepet_id","=","s.id")
            ->where("s.kullanici_id",auth()->id())
            ->whereRaw("si.id is null")
            ->orderByDesc("s.created_at")
            ->select("s.id")
            ->first();

        if (!is_null($aktif_sepet)) return $aktif_sepet->id;
    }
}
