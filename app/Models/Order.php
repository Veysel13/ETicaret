<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $table="orders";

    public $guarded=[];

    public function cart(){
        return $this->belongsTo('App\Models\Cart');
    }
}
