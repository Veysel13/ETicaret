<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartProduct extends Model
{
    use SoftDeletes;
    protected $table="cart_products";

    public $guarded=[];

    public function product(){
        return $this->belongsTo('App\Models\Product',"product_id");
    }
}
